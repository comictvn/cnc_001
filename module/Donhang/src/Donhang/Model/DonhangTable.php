<?php 
namespace Donhang\Model;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
class DonhangTable
{
	protected  $tableGateway;
	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway=$tableGateway;
	}
	
	public function fetchAll($dbAdapter)
	{
		$sql=new Sql($dbAdapter);
		$select = $sql->select();
		$select->from('customers');
		$select->columns(array('customers_id', 'customers_name', 'address', 'phone_number', 'delivery_address', 'email'));
		$select->join('bill','bill.customers_id=customers.customers_id',array('bill_id', 'bill_date', 'bill_value','payment','content','active'),$select::JOIN_INNER);
		$select->join('detail_bill','bill.bill_id=detail_bill.bill_id',array('id', 'qty'),$select::JOIN_INNER);
		$select->join('product','product.id=detail_bill.id',array('proid','proname', 'price','image'),$select::JOIN_INNER);
		$select->order(array('bill.bill_date DESC'));
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$product=array();
		for($i=0;$i<$results->count();$i++)
		{
			$kq=$results->current();
			$product[]=$kq;
			$results->next();
		}
		return $product;
	}
	
	public function updatetinhtrang($dbAdapter, $data)
	{
		$sql = new Sql($dbAdapter);
		$insert = $sql->update('bill');
		$insert->set(array('active'=>$data['active']));
		$insert->where(array('bill_id'=>$data['bill_id']));
		$selectString = $sql->getSqlStringForSqlObject($insert);
		$results = $dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
	}
	
	public function getCustomers($MaKH)
	{
		$MaKH =(int)$MaKH;
		$rowset=$this->tableGateway->select(array('MaKH'=>$MaKH));
		$row=$rowset->current();
		if(!$row){
			throw new \Exception("Không có khách hàng $MaKH");
		}
		return $row;
	}
	public function saveCustomers(Customers $customer)
	{
		$data=array(
			'customers_name'=>$customer->customers_name,
			'address'=>$customer->address,
			'phone_number'=>$customer->phone_number,
			'delivery_address'=>$customer->delivery_address,
			'email'=>$customer->email,
				);
		$customers_id=(int)$customer->customers_id;
		if($customers_id==0)
		{
			$this->tableGateway->insert($data);
			return $this->tableGateway->lastInsertValue;//Trả về id mới
		}
		else
		{
			if($this->getCustomers($customers_id))
			{
				$this->tableGateway->update($data,array('customers_id'=>$customers_id));
			}
			else
				throw new \Exception('Không có khách hàng '. $customers_id);
		}
	}
	public function saveBill($dbAdapter,$data)
	{
		/*$sql=new Sql($dbAdapter);
		$insert = $sql->insert('hoadon');
		$insert->values($data,$insert::VALUES_MERGE);
		$statement = $sql->prepareStatementForSqlObject($insert);
		$results = $statement->execute();
		return $dbAdapter->lastInsertValue;//Trả về id mới*/
		$billTable = new TableGateway('bill', $dbAdapter);
		$billTable->insert($data);
		return $billTable->lastInsertValue;//Trả về id mới
	}
	public function saveDetail_bill($dbAdapter,$data)
	{
		$sql=new Sql($dbAdapter);
		$insert = $sql->insert('detail_bill');
		$insert->values($data,$insert::VALUES_MERGE);
		$statement = $sql->prepareStatementForSqlObject($insert);
		$statement->execute();
		//$results = $statement->execute();
	}
	
	public function delete($id)
	{
		$query = $this->tableGateway->delete(array('customers_id'=>$id));
	
		return $query;
	}
	public function fetchbill($dbAdapter,$customers_id)
	{
		$sql=new Sql($dbAdapter);
		$select = $sql->select();
		$select->from('customers');
		$select->columns(array('customers_id', 'customers_name', 'address', 'phone_number', 'delivery_address', 'email'));
		$select->join('bill','bill.customers_id=customers.customers_id',array('bill_id', 'bill_date', 'bill_value','payment','content','active'),$select::JOIN_INNER);
		$select->join('detail_bill','bill.bill_id=detail_bill.bill_id',array('id', 'qty'),$select::JOIN_INNER);
		$select->join('product','product.id=detail_bill.id',array('proid','proname', 'price','image'),$select::JOIN_INNER);
		$select->where(array('customers.customers_id'=>$customers_id));
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$product=array();
		for($i=0;$i<$results->count();$i++)
		{
			$kq=$results->current();
			$product[]=$kq;
			$results->next();
		}
		return $product;
	}
}
?>