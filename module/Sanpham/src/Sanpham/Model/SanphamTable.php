<?php
namespace Sanpham\Model;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Insert;
class SanphamTable
{
	protected $tableGateway;
	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway=$tableGateway;
	}
	
	public function getAll()
	{
		$select = $this->tableGateway->getSql()->select()->order(array('id DESC'))->where('active != 0');
		$resultSet = $this->tableGateway->selectWith($select);
		$resultSet->buffer();
		return $resultSet;
	}

	public function search($name) {
		$select = $this->tableGateway->getSql()->select()->where("proname like '%$name%'")->order(array('id DESC'))->limit(5);
		$resultSet = $this->tableGateway->selectWith($select);
		$resultSet->buffer();
		return $resultSet;
	}

	public function getOtherProduct($limit)
	{
		$select = $this->tableGateway->getSql()->select()->order(array('id DESC'))->where('active != 0')->limit($limit);
		$resultSet = $this->tableGateway->selectWith($select);
		$resultSet->buffer();
		return $resultSet;
	}
	
	public function getAllcate($id)
	{
		$select = $this->tableGateway->getSql()->select()->join('catepro', 'catepro.idpro = product.id', array('idpro', 'idcate'))
		->join('procate', 'procate.id = catepro.idcate', array('cate_alias'=>'alias'))
		->where(array(/*'procate.alias'=>$id,*/'product.active'=>1))
		->order(array('product.id DESC'));
		$resultSet = $this->tableGateway->selectWith($select);
		$resultSet->buffer();
		return $resultSet;
	}
	
	public function getAllcateindex()
	{
		$select = $this->tableGateway->getSql()->select()->order(array('product.id DESC'))->join('catepro', 'catepro.idpro = product.id',array('idpro', 'idcate'))
		->join('procate', 'procate.id = catepro.idcate',array('id', 'name'))
		->where(array('product.active'=>1));
	
		$resultSet = $this->tableGateway->selectWith($select);
		$resultSet->buffer();
		return $resultSet;
	}
	
	
	public function getcate($dbAdapter)
	{
		$sql = new Sql($dbAdapter);
		$select = $sql->select();
		$select->from('procate');
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$product=array();
		for($i = 0; $i < $results->count(); $i++)
		{
			$kq=$results->current();
			$product[]=$kq;
			$results->next();
		}
		return $product;
	}
	
	public function getcateid($dbAdapter, $id)
	{
		$sql = new Sql($dbAdapter);
		$select = $sql->select();
		$select->from('catepro');
		$select->where(array('idpro'=>$id));
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$product=array();
		for($i = 0; $i < $results->count(); $i++)
		{
		$kq=$results->current();
		$product[]=$kq;
		$results->next();
		}
		return $product;
	}
	
	public function getproimage($dbAdapter, $id)
	{
		$sql = new Sql($dbAdapter);
		$select = $sql->select();
		$select->from('imagepro');
		$select->where(array('idpro'=>$id));
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$product=array();
		for($i = 0; $i < $results->count(); $i++)
		{
		$kq=$results->current();
		$product[]=$kq;
		$results->next();
		}
		return $product;
	}
	
	public function getcontact($dbAdapter)
	{
		$sql = new Sql($dbAdapter);
		$select = $sql->select();
		$select->from('infocontact');
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$product=array();
		for($i = 0; $i < $results->count(); $i++)
		{
		$kq=$results->current();
		$product[]=$kq;
		$results->next();
		}
		return $product;
	}
	
	public function getcontactid($dbAdapter, $id)
	{
		$sql = new Sql($dbAdapter);
		$select = $sql->select();
		$select->from('proinfocontact');
		$select->where(array('idpro'=>$id));
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$product=array();
		for($i = 0; $i < $results->count(); $i++)
		{
		$kq=$results->current();
		$product[]=$kq;
		$results->next();
		}
		return $product;
	}
	
	
	public function getcateparam($dbAdapter)
	{
		$sql = new Sql($dbAdapter);
		$select = $sql->select();
		$select->from(array('p' => 'param'))->join(array('pc' => 'procate'), 'pc.id = p.category') ;
		$select->where(array('pc.active'=>1));
		$select->group('pc.name');
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$product=array();
		for($i = 0; $i < $results->count(); $i++)
		{
		$kq=$results->current();
		$product[$i]=array('id'=>$kq['id'], 'name'=>$kq['name']);
		$results->next();
		}
		return $product;
	}
	
	public function getseo($dbAdapter)
	{
		$sql = new Sql($dbAdapter);
		$select = $sql->select();
		$select->from('seopage');
		$select->where(array('page'=>'Sanpham'));
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
	
		$kp = $results->current();
	
		return $kp;
	}
	
	public function getcateparam0($dbAdapter)
	{
		$sql = new Sql($dbAdapter);
		$select = $sql->select();
		$select->from('param');
		$select->where(array('parents'=>0, 'active'=>1));
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$product=array();
		for($i = 0; $i < $results->count(); $i++)
		{
		$kq=$results->current();
		$product[$i]=array('id'=>$kq['id'], 'name'=>$kq['name'],'category'=>$kq['category'],'parents'=>$kq['parents'] );
		$results->next();
		}
		return $product;
	}
	
	public function getcateparam1($dbAdapter)
	{
		$sql = new Sql($dbAdapter);
		$select = $sql->select();
		$select->from('param');
		$select->where('parents != 0 and active=1');
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$product=array();
		for($i = 0; $i < $results->count(); $i++)
		{
		$kq=$results->current();
		$product[$i]=array('id'=>$kq['id'], 'name'=>$kq['name'],'category'=>$kq['category'],'parents'=>$kq['parents'] );
		$results->next();
		}
		return $product;
	}
	
	public function getparamdetails($dbAdapter, $id)
	{
		$sql = new Sql($dbAdapter);
		$select = $sql->select();
		$select->from('paramdetail');
		$select->where(array('idpro'=>$id));
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$product=array();
		for($i = 0; $i < $results->count(); $i++)
		{
		$kq=$results->current();
		$product[]=$kq;
		$results->next();
		}
		return $product;
	}
	
	public function insertcate($dbAdapter, $data)
	{
		$sql = new Sql($dbAdapter);
		$insert = $sql->insert('catepro');
		$insert->values($data);
		$selectString = $sql->getSqlStringForSqlObject($insert);
		$results = $dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
	}
	
	public function deletecate($dbAdapter, $data)
	{
		$sql = new Sql($dbAdapter);
		$delete = $sql->delete('catepro');
		$delete->where(array('idpro'=>$data['idpro']));
		$selectString = $sql->getSqlStringForSqlObject($delete);
		$results = $dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
	}
	
	public function deletecontact($dbAdapter, $data)
	{
		$sql = new Sql($dbAdapter);
		$delete = $sql->delete('proinfocontact');
		$delete->where(array('idpro'=>$data['idpro']));
		$selectString = $sql->getSqlStringForSqlObject($delete);
		$results = $dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
	}
	
	public function deleteimage($dbAdapter, $data)
	{
		$sql = new Sql($dbAdapter);
		$delete = $sql->delete('imagepro');
		$delete->where(array('idpro'=>$data['idpro']));
		$selectString = $sql->getSqlStringForSqlObject($delete);
		$results = $dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
	}
	
	public function insertparam($dbAdapter, $data)
	{
		$sql = new Sql($dbAdapter);
		$insert = $sql->insert('paramdetail');
		$insert->values($data);
		$selectString = $sql->getSqlStringForSqlObject($insert);
		$results = $dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
	}
	
	public function updateparam($dbAdapter, $data)
	{
		$sql = new Sql($dbAdapter);
		$insert = $sql->update('paramdetail');
		$insert->set($data);
		$insert->where(array('idpro'=>$data['idpro'], 'idparam'=>$data['idparam']));
		$selectString = $sql->getSqlStringForSqlObject($insert);
		$results = $dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
	}
	
	public function insertimage($dbAdapter, $data)
	{
		$sql = new Sql($dbAdapter);
		$insert = $sql->insert('imagepro');
		$insert->values($data);
		$selectString = $sql->getSqlStringForSqlObject($insert);
		$results = $dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
	}
	
	public function insertinfocontact($dbAdapter, $data)
	{
		$objInsert = new Insert('infocontact');
		$objInsert->values($data);
		$sql = new Sql($dbAdapter);
		return $result = $sql->prepareStatementForSqlObject($objInsert)->execute()->getGeneratedValue();
	}
	
	public function insertprocontact($dbAdapter, $data)
	{
		$sqlObject = new Insert('proinfocontact');
		$sqlObject->values($data);
		$sql = new Sql($dbAdapter);
		return $result = $sql->prepareStatementForSqlObject($sqlObject)->execute()->getGeneratedValue();
	}
	
	public function getmemberID($id)
	{
		$id=(int)$id;
		$rowset = $this->tableGateway->select(array('id'=>$id));
		$result = $rowset->current();
		if(!$result){
			throw new \Exception("Could not find row $id");
		}
		return $result;
	}
	
	public function getdetail($id)
	{
		$id = $id;
		$rowset = $this->tableGateway->select(array('alias'=>$id));
		$result = $rowset->current();
		if(!$result){
			throw new \Exception("Could not find row $id");
		}
		return $result;
	}

	public function cartlist($ds_masp)
	{
		$resultSet = $this->tableGateway->select(array("id in($ds_masp)"));
		return $resultSet;
	}

	public function saveMember(Sanpham $member)
	{
		$data = array(
				'id'=>$member->id,
				'proid'=>$member->proid,
				'proname'=>$member->proname,
				'alias'=>$member->alias,
				'order'=>$member->order,
				'price'=>$member->price,
				'pricesales'=>$member->pricesales,
				'salesto'=>$member->salesto,
				'salesfrom'=>$member->salesfrom,
				'vat'=>$member->vat,
				'district'=>$member->district,
				'tag'=>$member->tag,
				'active'=>$member->active,
				'video'=>$member->video,
				'quantity'=>$member->quantity,
				'pronew'=>$member->pronew,
				'proselling'=>$member->proselling,
				'procomming'=>$member->procomming,
				'proindex'=>$member->proindex,
				'image'=>$member->image,
				'summary'=>$member->summary,
				'description'=>$member->description,
				'meta'=>$member->meta,
				'keyword'=>$member->keyword,
		);
		$id=(int)$member->id;
		
		if($id==0)
		{
			$this->tableGateway->insert($data);
			return $this->tableGateway->lastInsertValue;
		}
		else
		{
			if($this->getmemberID($id))
			{
				$this->tableGateway->update($data, array('id'=>$id));
			}
			else
			{
				throw new \Exception('Form id does not exist');
			}
		}
	}
	
	public function delete($id)
	{
		$query = $this->tableGateway->delete(array('id'=>$id));
		
		return $query;
	}
}