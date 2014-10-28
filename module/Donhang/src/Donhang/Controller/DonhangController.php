<?php
namespace Donhang\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Donhang\Form\DonhangForm;
use Giohang;
use Donhang\Model\Donhang;
use Donhang\Model\DonhangTable;
use Zend\Authentication\AuthenticationService;
use Zend\Mail;
use Zend\Mail\Message;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;

class DonhangController extends AbstractActionController
{
	var $giohang;
	public function __construct()
	{
		$this->giohang = new Giohang\Controller\GiohangController();
		$_SESSION['so_sp']=$this->giohang->so_sp();
		$_SESSION['tong_tien']=$this->giohang->tong_tien();
	}
	
	protected $sanphamTable;
	public function getSanphamTable()
	{
		if(!$this->sanphamTable)
		{
			$sm=$this->getServiceLocator();
			$this->sanphamTable=$sm->get('Sanpham\Model\SanphamTable');
		}
		return $this->sanphamTable;
	}
	public function getDonhangTable()
	{
		if(!$this->DonhangTable)
		{
			$sm=$this->getServiceLocator();
			$this->DonhangTable=$sm->get('Donhang\Model\DonhangTable');
		}
		return $this->DonhangTable;
	}
    protected $DonhangTable;
    
    
    
    public function addAction()
    {
    	date_default_timezone_set('Asia/Saigon');
    	$giohang=new Giohang\Controller\GiohangController();
    	$_SESSION['tong_tien']=$giohang->tong_tien();
    	$_SESSION['so_sp']=$giohang->so_sp();
    	$form = new DonhangForm();
    	$form->get('submit')->setValue('Cập nhật');
    	$request=$this->getRequest();
    	$giohang1 = $this->giohang->product_gio_hang();
    	if(!empty($giohang1))
    	{
    		$ds_masp=array();
    		foreach($giohang1 as $key =>$value)
    		{
    			$ds_masp[]=$key;
    		}
    		$ds_masp=implode(',',$ds_masp);
    		$results=$this->getSanphamTable()->cartlist($ds_masp);
    		$product_giohang=array();
    		 
    		foreach ($results as $row)
    		{
    			$row->qty = $giohang1[$row->id];
    			$product_giohang[]=$row;
    	
    		}
    		 
    	}
    	if($request->isPost())
    	{
    		$Donhang = new Donhang();
    		$form->setInputFilter($Donhang->getInputFilter());
    		$form->setData($request->getPost());
    		if($form->isValid())
    		{
    			//lưu thông tin khách hàng
    			$dulieu = $form->getData();
    		
    			$Donhang->exchangeArray($dulieu);
    			$Donhang_id= $this->getDonhangTable()->saveDonhang($Donhang);
    			//Tạo thông tin hóa đơn
    			$data=array(
    					'bill_date'=>date('Y-m-d'),
    					'Donhang_id'		=>$Donhang_id,
    					'bill_value'	=>$_SESSION['tong_tien'],
    					'payment'=> $request->getPost('OrderPaymentMethod'),
    					'content'=>$request->getPost('noidung'),
    					);
    			$sm=$this->getServiceLocator();
    			$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    			
    			$id_bill= $this->getDonhangTable()->saveBill($dbAdapter,$data);
    			$ds_product = $giohang->product_gio_hang();
    			//Lưu giỏ hàng và bảng chi tiết giỏ hàng
    			foreach ($ds_product as $id =>$qty)
    			{
    				$data=array(
    					'bill_id'=>$id_bill,
    					'id'=>$id,
    					'qty'=>$qty,	
    						);
    				$this->getDonhangTable()->saveDetail_bill($dbAdapter,$data);
    			}
    			
    			$data = $form->getData();
    			
    			$noidung = '<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<tbody>
						<tr>
							<td align="center" bgcolor="#ffffff">
								<table width="100%" cellpadding="0" cellspacing="0" border="0"
									style="font-family: Arial, Helvetica, sans-serif">
									<tbody>
				    			
										<tr width="600">
											<td width="600" height="47" align="center">
												<table width="600" cellpadding="0" cellspacing="0" border="0"
													bgcolor="#00afe3">
													<tbody>
														<tr>
															<td height="11"></td>
														</tr>
														<tr width="600">
															<td width="600">
																<table cellpadding="0" cellspacing="0" border="0"
																	width="600">
																	<tbody>
																		<tr align="center">
																			<td width="10"></td>
																			<td align="left" width="146"><a href="#14591ffe825c68e9_"><img
																					style="background-color: #cccccc" width="146"
																					title="OK DEAL"></a></td>
																			<td width="344"></td>
																			<td align="right" width="110">
																				<table cellpadding="0" cellspacing="0" border="0">
																					<tbody>
																						<tr>
																							<td align="right" width="14"><img
																								style="background-color: #cccccc" width="14"
																								title="hotline"></td>
																							<td align="right" width="80"><span
																								style="color: #ffff00; font-family: Arial, Helvetica, Geneva, sans-serif; font-size: 16px; font-weight: bold">
																									0902.614.239 </span></td>
																						</tr>
																					</tbody>
																				</table>
																			</td>
																			<td width="10"></td>
																		</tr>
																	</tbody>
																</table>
															</td>
														</tr>
														<tr>
															<td height="10"></td>
														</tr>
				    			
													</tbody>
												</table>
											</td>
										</tr>
										<tr>
											<td width="600" align="center" bgcolor="#ffffff">
												<table width="600" cellpadding="0" cellspacing="0" border="0">
													<tbody>
														<tr height="20">
															<td></td>
															<td width="600"></td>
														</tr>
														<tr align="center" width="600">
															<td width="10"></td>
															<td width="590" align="left"><span
																style="color: #00afe3; font-family: Arial, Helvetica, Geneva, sans-serif; font-size: 20px; font-weight: bold">
																	THÔNG BÁO </span></td>
														</tr>
														<tr height="20">
															<td></td>
															<td width="590"></td>
														</tr>
														<tr height="1">
															<td height="1" width="10" bgcolor="#f1f1f1"></td>
															<td height="1" width="600" bgcolor="#f1f1f1"></td>
														</tr>
													</tbody>
												</table>
											</td>
										</tr>
										<tr>
											<td height="42">
												<table width="100%" cellpadding="0" cellspacing="0" border="0"
													bgcolor="#ffffff">
													<tbody>
														<tr align="center">
															<td width="600" align="center">
																<table width="600" cellpadding="0" cellspacing="0"
																	border="0" bgcolor="#ffffff">
																	<tbody>
																		<tr>
																			<td height="42" width="600" style="color: #ffffff"></td>
																		</tr>
																	</tbody>
																</table>
															</td>
														</tr>
													</tbody>
												</table>
											</td>
										</tr>
				    			
				    			
										<tr align="center">
											<td width="600" bgcolor="#ffffff">
												<table width="600" cellpadding="0" cellspacing="0" border="0"
													bgcolor="#ffffff" style="color: #5a5a5a">
													<tbody>
														<tr width="600">
															<td width="600">
				    			
																<table width="100%"
																	style="font-family: Arial, Helvetica, sans-serif; font-size: 13px; background: #ffffff"
																	cellpadding="0" cellspacing="0">
																	<tbody>
																		<tr>
																			<td width="4%"></td>
																			<td width="558" align="left"><strong>Chào' . $_POST['Donhang_name'] . '
																			,</strong></td>
																			<td width="25"></td>
																		</tr>
																		<tr>
																			<td width="4%"></td>
																			<td height="10">&nbsp;</td>
																			<td></td>
																		</tr>
																		<tr>
																			<td width="4%"></td>
																			<td style="color: #555; line-height: 18px">Chúng tôi đã
																				nhận được đơn đặt hàng của bạn tại <a
																				href="http://www.okdeal.com.vn" target="_blank">www.okdeal.com.vn</a>.
																				Chúng tôi sẽ giao hàng cho bạn trong vòng 2 đến 7 ngày
																				làm việc.
				    			
																			</td>
																			<td></td>
																		</tr>
																		<tr>
																			<td width="25"></td>
																			<td>&nbsp;</td>
																			<td></td>
																		</tr>
																		<tr>
																			<td width="25"></td>
																			<td><strong>Đơn hàng</strong> 1712142 mua lúc 4/24/2014
																				11:30:20 AM</td>
																			<td></td>
																		</tr>
																		<tr>
																			<td></td>
																			<td>&nbsp;</td>
																			<td></td>
																		</tr>
																		<tr>
																			<td width="4%"></td>
																			<td>
																				<table width="100%" border="0" cellspacing="0"
																					cellpadding="0"
																					style="border-collapse: collapse; line-height: 25px; font-size: 13px; font-family: Arial, Helvetica, sans-serif">
																					<tbody>
																						<tr>
																							<th width="50%" align="center"
																								style="background: #efefef; color: #000000; padding: 5px; border: 1px solid #e0e0e0">Phiếu
																								/ Sản phẩm</th>
																							<th width="17%" align="center"
																								style="background: #efefef; color: #000000; padding: 5px; border: 1px solid #e0e0e0">Đơn
																								giá</th>
																							<th width="13%" align="center"
																								style="background: #efefef; color: #000000; padding: 5px; border: 1px solid #e0e0e0">Số
																								lượng</th>
																							<th width="20%" align="center"
																								style="background: #efefef; color: #000000; padding: 5px; border: 1px solid #e0e0e0">Thành
																								tiền</th>
																						</tr>';
    																					foreach ($product_giohang as $row){
    																						$noidung .= '<tr>
    																						<td width="50%" align="left" valign="middle" style="padding:3px;border-bottom:1px dotted #e0e0e0;line-height:16px;border-left:1px solid #e0e0e0;border-right:1px solid #e0e0e0;color:#555">' . $row->product_name . '</td>
    																						<td width="17%" align="center" valign="middle" style="padding:3px;border-bottom:1px dotted #e0e0e0;border-left:1px solid #e0e0e0;border-right:1px solid #e0e0e0;color:#555">' . number_format ( $row->sales ) . '</td>
    																						<td width="13%" align="center" valign="middle" style="padding:3px;border-bottom:1px dotted #e0e0e0;border-left:1px solid #e0e0e0;border-right:1px solid #e0e0e0;color:#555">' . $row->qty . '</td>
    																						<td width="20%" align="right" valign="middle" style="padding:3px;border-bottom:1px dotted #e0e0e0;border-left:1px solid #e0e0e0;border-right:1px solid #e0e0e0;color:#555">' . number_format($row->sales * $row->qty) . '</td>
    																						</tr>';
    																					}
    		
																						$noidung .= '
																						<tr>
																							<td align="center" valign="middle"
																								style="padding: 3px; border-bottom: 1px dotted #e0e0e0; border-left: 1px solid #e0e0e0; border-right: 1px solid #e0e0e0; color: #555">&nbsp;</td>
																							<td colspan="2" align="right" valign="middle"
																								style="padding: 3px; border-bottom: 1px dotted #e0e0e0; border-left: 1px solid #e0e0e0; border-right: 1px solid #e0e0e0; color: #555"><strong>Tổng
																									tiền</strong>&nbsp;&nbsp;</td>
																							<td align="right" valign="middle"
																								style="padding: 3px; border-bottom: 1px dotted #e0e0e0; border-left: 1px solid #e0e0e0; border-right: 1px solid #e0e0e0; color: #555"><strong>' . number_format($_SESSION['tong_tien']) . 'đ</strong>&nbsp;&nbsp;</td>
																						</tr>
																						<tr>
																							<td align="center" valign="middle"
																								style="padding: 3px; border-bottom: 1px dotted #e0e0e0; border-left: 1px solid #e0e0e0; border-right: 1px solid #e0e0e0; color: #555">&nbsp;</td>
																							<td colspan="2" align="right" valign="middle"
																								style="padding: 3px; border-bottom: 1px dotted #e0e0e0; border-left: 1px solid #e0e0e0; border-right: 1px solid #e0e0e0; color: #555"><strong>Phí
																									giao hàng</strong>&nbsp;&nbsp;</td>
																							<td align="right" valign="middle"
																								style="padding: 3px; border-bottom: 1px dotted #e0e0e0; border-left: 1px solid #e0e0e0; border-right: 1px solid #e0e0e0; color: #555"><strong>Miễn
																									phí</strong>&nbsp;&nbsp;</td>
																						</tr>
																						<tr>
																							<td align="center" valign="middle"
																								style="padding: 3px; border-bottom: 1px dotted #e0e0e0; border-left: 1px solid #e0e0e0; border-right: 1px solid #e0e0e0; color: #555">&nbsp;</td>
																							<td colspan="2" align="right" valign="middle"
																								style="padding: 3px; border-bottom: 1px dotted #e0e0e0; border-left: 1px solid #e0e0e0; border-right: 1px solid #e0e0e0; color: #555"><strong>Sử
																									dụng tiền thưởng</strong>&nbsp;&nbsp;</td>
																							<td align="right" valign="middle"
																								style="padding: 3px; border-bottom: 1px dotted #e0e0e0; border-left: 1px solid #e0e0e0; border-right: 1px solid #e0e0e0; color: #555"><strong>-0đ</strong>&nbsp;&nbsp;</td>
																						</tr>
																						<tr>
																							<td align="center" valign="middle"
																								style="padding: 3px; border-bottom: 1px dotted #e0e0e0; border-left: 1px solid #e0e0e0; border-right: 1px solid #e0e0e0; color: #555">&nbsp;</td>
																							<td colspan="2" align="right" valign="middle"
																								style="padding: 3px 0px; border-bottom: 1px dotted #e0e0e0; border-left: 1px solid #e0e0e0; border-right: 1px solid #e0e0e0; color: #555"><strong>Sử
																									dụng tiền giảm giá </strong>&nbsp;&nbsp;</td>
																							<td align="right" valign="middle"
																								style="padding: 3px; border-bottom: 1px dotted #e0e0e0; border-left: 1px solid #e0e0e0; border-right: 1px solid #e0e0e0; color: #555"><strong>-0đ</strong>&nbsp;&nbsp;</td>
																						</tr>
																						<tr>
																							<td align="center" valign="middle"
																								style="padding: 3px; border-bottom: 1px dotted #e0e0e0; border-left: 1px solid #e0e0e0; border-right: 1px solid #e0e0e0; color: #555; line-height: 16px"></td>
																							<td colspan="2" align="right" valign="middle"
																								style="padding: 3px; border-bottom: 1px dotted #e0e0e0; border-left: 1px solid #e0e0e0; border-right: 1px solid #e0e0e0; color: #555"><strong>Số
																									tiền phải trả</strong>&nbsp;&nbsp;</td>
																							<td align="right" valign="middle"
																								style="padding: 3px; border-bottom: 1px dotted #e0e0e0; border-left: 1px solid #e0e0e0; border-right: 1px solid #e0e0e0; color: #555"><strong>' . number_format($_SESSION['tong_tien']) . 'đ</strong>&nbsp;&nbsp;</td>
																						</tr>
																						<tr>
																							<td colspan="4" align="left" valign="middle"
																								style="padding: 3px; border-bottom: 1px dotted #e0e0e0; border-left: 1px solid #e0e0e0; border-right: 1px solid #e0e0e0; color: #555; line-height: 16px">&nbsp;&nbsp;<strong>Hình
																									thức thanh toán: Tiền mặt</strong></td>
																						</tr>
				    			
																					</tbody>
																				</table>
																			</td>
																			<td width="4%"></td>
																		</tr>
																		<tr>
																			<td width="4%"></td>
																			<td>&nbsp;</td>
																			<td></td>
																		</tr>
																		<tr>
																			<td width="4%"></td>
																			<td><strong>Địa chỉ:</strong> 37/14 Đường Cầu Xéo,
																				Phường Tân Quý, Quận Tân Phú, TP Hồ Chí Minh</td>
																			<td></td>
																		</tr>
																		<tr>
																			<td width="25"></td>
																			<td>&nbsp;</td>
																			<td width="4%"></td>
																		</tr>
																		<tr>
																			<td width="25"></td>
																			<td><span style="font-size: 13px; padding: 5px 0">OK DEAL cảm ơn và rất mong tiếp tục nhận được sự ủng hộ của
																					bạn</span><br></td>
																			<td width="25"></td>
																		</tr>
																		<tr>
																			<td colspan="3" bgcolor="#FFFFFF">&nbsp;</td>
																		</tr>
																	</tbody>
																</table>
																<table cellpadding="0" cellspacing="0" width="600">
																	<tbody>
																		<tr height="30">
																			<td width="600"></td>
																		</tr>
																		<tr height="3">
																			<td height="3" width="600"
																				style="background-color: #f2f2f2"></td>
																		</tr>
																		<tr height="30">
																			<td width="600"></td>
																		</tr>
																	</tbody>
																</table>
																<table cellpadding="0" cellspacing="0" width="600"
																	style="font-size: 16px">
																	<tbody>
																		<tr height="30">
																			<td width="600"><b
																				style="font-size: 18px; color: #464646">Tại sao nên chọn
																					OK DEAL ?</b></td>
																		</tr>
																		<tr height="20">
																			<td width="600"></td>
																		</tr>
																		<tr>
																			<td>
																				<table cellspacing="0">
																					<tbody>
																						<tr>
																							<td width="200" align="left">
																								<table cellspacing="0">
																									<tbody>
																										<tr>
																											<td><img width="31" height="43"></td>
																											<td>
																												<table>
																													<tbody>
																														<tr>
																															<td><b
																																style="font-size: 13px; color: #00afe3">Chi
																																	tiêu hiệu quả</b></td>
																														</tr>
																														<tr>
																															<td>
																																<p
																																	style="color: #505050; font-size: 11px; margin: 0">Đa
																																	dạng sản phẩm &amp; dịch vụ.</p>
																																<p
																																	style="color: #505050; font-size: 11px; margin: 0">Chất
																																	lượng cao với giá ưu đãi.</p>
																															</td>
																														</tr>
																													</tbody>
																												</table>
																											</td>
																										</tr>
																									</tbody>
																								</table>
																							</td>
																							<td width="5"></td>
																							<td width="200" align="center">
																								<table cellspacing="0">
																									<tbody>
																										<tr>
																											<td><img width="39" height="43"></td>
																											<td>
																												<table>
																													<tbody>
																														<tr>
																															<td><b
																																style="font-size: 13px; color: #00afe3">An
																																	tâm khi mua sắm</b></td>
																														</tr>
																														<tr>
																															<td>
																																<p
																																	style="color: #505050; font-size: 11px; margin: 0">Đổi
																																	trả thoải mái.</p>
																																<p
																																	style="color: #505050; font-size: 11px; margin: 0">Trả
																																	tiền khi nhận hàng.</p>
																															</td>
																														</tr>
																													</tbody>
																												</table>
																											</td>
																										</tr>
																									</tbody>
																								</table>
				    			
																							</td>
																							<td width="5"></td>
																							<td width="200" align="right">
																								<table cellspacing="0">
																									<tbody>
																										<tr>
																											<td><img width="39" height="43"></td>
																											<td>
																												<table>
																													<tbody>
																														<tr>
																															<td><b
																																style="font-size: 13px; color: #00afe3">Dịch
																																	vụ chu đáo</b></td>
																														</tr>
																														<tr>
																															<td>
																																<p
																																	style="color: #505050; font-size: 11px; margin: 0">Giao
																																	hàng nhanh trong 24h.</p>
																																<p
																																	style="color: #505050; font-size: 11px; margin: 0">
																																	Hotline phục vụ cả T7 và CN.</p>
																															</td>
																														</tr>
																													</tbody>
																												</table>
																											</td>
																										</tr>
																									</tbody>
																								</table>
				    			
																							</td>
																						</tr>
																					</tbody>
																				</table>
																			</td>
																		</tr>
																	</tbody>
																</table>
				    			
																<table cellpadding="0" cellspacing="0">
																	<tbody>
																		<tr height="20">
																			<td></td>
																		</tr>
																	</tbody>
																</table>
															</td>
				    			
														</tr>
													</tbody>
												</table>
												<table width="600" cellpadding="0" cellspacing="0" border="0"
													bgcolor="#ffffff" style="color: #dfdfe0; text-align: center">
													<tbody>
														<tr height="20">
															<td colspan="3"></td>
														</tr>
														<tr height="1">
															<td height="1" width="600" bgcolor="#f1f1f1"></td>
														</tr>
														<tr height="10">
															<td colspan="3"></td>
														</tr>
														<tr>
															<td width="600">
																<p style="font-size: 16px; color: #00afe3">
																	<b>Công ty Cồ Phần Hoàng Nguyên Việt Nam</b>
																</p>
																<p style="margin: 0; font-size: 13px; color: #505050">
																	<b>VP HCM</b>: 37/14 Cầu Xéo, Phường Tân Quý, Q. Tân Phú
																	
																</p>
																
																<p style="margin: 0; font-size: 13px; color: #505050">
																	Email: <a href="mailto:kinhdoanh@dongphucviet.com"
																		style="color: #505050" target="_blank"><i>kinhdoanh@dongphucviet.com</i></a>
																</p>
															</td>
														</tr>
														<tr height="20">
															<td colspan="3"></td>
														</tr>
				    			
													</tbody>
												</table>
											</td>
										</tr>
										<tr>
											<td width="600">&nbsp;</td>
										</tr>
										<tr>
											<td width="600" height="60"></td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>' ;
    			
    			
    			$html = new MimePart($noidung);
    			$html->type = "text/html";
    				
    			$pathToImage = 'img/hinhmail/1.png';
    			$image = new MimePart(fopen($pathToImage, 'r'));
    			$image->encoding = "base64";
    			$image->type = "image/jpeg";
    				
    			$body = new MimeMessage();
    			$body->setParts(array($html, $image));
    				
    			$mail = new Mail\Message();
    			$mail->setEncoding('UTF-8');
    			$mail->setBody($body);
    			$mail->setFrom($_POST['email'], 'Thông tin xác nhận đơn hàng');
    			$mail->addTo($_POST['email']);
    			$mail->setSubject('Thông tin xác nhận đơn hàng');
    				
    			$smtpOptions = new \Zend\Mail\Transport\SmtpOptions();
    				
    			$smtpOptions->setHost('okdeal.com.vn')
    			->setConnectionClass('login')
    			->setName('okdeal.com.vn')
    			->setConnectionConfig(array(
    					'username'=>'info@okdeal.com.vn',
    					'password'=>'duynghia',
    					'ssl'=>'tls',
    			));
    			$transport = new \Zend\Mail\Transport\Smtp($smtpOptions);
    			try {
    				$transport->send($mail);
    				$giohang->huygiohang();
    				return $this->redirect()->toRoute('Donhang',array('action'=>'print','id'=>$Donhang_id));
    				exit();
    			}
    			catch (Exception $e)
    				
    			{
    				echo "Không thành công";
    				exit();
    			}
    			  	
    		}
    	}
    	
    	$productRandom = $this->getSanphamTable()->fetchAllrandom();
    	$productMax = $this->getSanphamTable()->fetchproductspecial();
    	$Subject = $this->getSubjectTable()->fetchAll();
    	$Adver = $this->getAdverTable()->fetchAll();
    	return new ViewModel(array('form'=>$form,'title'=>'Thanh toán',
    			'productRandom'=>$productRandom,
    			'subject'=>$Subject,
    			'productMax'=>$productMax,
    			'adver'=>$Adver,
    			'ds_sp'=>@$product_giohang,
    	));
    }
    //client
    public function printAction()
    {
    	date_default_timezone_set('Asia/Saigon');
    	$Donhang_id=(int)$this->params()->fromRoute('id',0);
    	//Đọc hóa đơn khách hàng
    	$sm=$this->getServiceLocator();
    	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');    	 
    	$bill= $this->getDonhangTable()->fetchbill($dbAdapter,$Donhang_id);
    	$productdif = $this->getSanphamTable()->fetchAllrandom();
    	return new ViewModel(array('bill'=>$bill,'title'=>'Đơn đặt hàng',
    			'sanphamkhac'=>$productdif,
    	));
    }
    
	public function billAction()
	{
		$auth = new AuthenticationService();
		if(!$auth->hasIdentity())
		{
			return $this->redirect()->toRoute('member',array('action'=>'login'));
		}
		$this->layout()->setTemplate('layout/layoutedit');
		$id=(int)$this->params()->fromRoute('id',0);
		//Đọc hóa đơn khách hàng
		$sm=$this->getServiceLocator();
		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		$bill= $this->getDonhangTable()->fetchbill($dbAdapter,$id);
		$request = $this->getRequest();
		if($request->isPost())
		{
			$id=(int)$this->params()->fromRoute('id',0);
		
			$data['bill_id'] = $id;
			$data['active'] = $_POST['active'];
			$this->getDonhangTable()->updatetinhtrang($dbAdapter,$data);
		}
		return new ViewModel(array('bill'=>$bill,'id'=>$id));
	}
	

	public function listAction()
	{
		$auth = new AuthenticationService();
		if(!$auth->hasIdentity())
		{
			return $this->redirect()->toRoute('member',array('action'=>'login'));
		}
		$this->layout()->setTemplate('layout/layoutadmin');
		$id=(int)$this->params()->fromRoute('id',0);
		//Đọc hóa đơn khách hàng
		$sm=$this->getServiceLocator();
		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		$bill= $this->getDonhangTable()->fetchAll($dbAdapter);
		
		return new ViewModel(array('donhang'=>$bill));
	}
	
	
	
	public function deleteAction()
    {
    	$auth = new AuthenticationService();
    	if(!$auth->hasIdentity())
    	{
    		return $this->redirect()->toRoute('member',array('action'=>'login'));
    	}
    	$this->layout()->setTemplate('layout/layoutadmin');
    	$id = (int) $this->params()->fromRoute('id', 0);
    	$this->getDonhangTable()->delete($id);
    	return $this->redirect()->toRoute('Donhang', array('action'=>'list'));
    }
}