<?php
namespace Danhmuc\Model;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
class DanhmucTable
{
	protected $tableGateway;
	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway=$tableGateway;
	}
	
	public function fetchAll()
	{
		$select = $this->tableGateway->getSql()->select()->order(array('id DESC'));
		$resultSet = $this->tableGateway->selectWith($select);
		$resultSet->buffer();
		return $resultSet;
	}
	
	
	public function getnewDanhmuc()
	{
		
		$rowset = $this->tableGateway->getSql()->select()->order(array('id DESC'))->limit(1);
		$resultSet = $this->tableGateway->selectWith($rowset);
		$result = $resultSet->current();
		
		return $result;
	}
	
	
	
	public function fetchDanhmuc($dbAdapter)
	{
		$sql = new Sql($dbAdapter);
		$select = $sql->select();
		$select->from('procate');
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$loai_sp = array();
		for($i = 0; $i<$results->count(); $i++)
		{
		$kp = $results->current();
		$loai_sp[$kp['id']]=$kp['name'];
				$results->next();
		}
		return $loai_sp;
	}
	
	public function getcateid($dbAdapter, $id)
	{
		$sql = new Sql($dbAdapter);
		$select = $sql->select();
		$select->from('catepro');
		$select->where(array('idcate'=>$id));
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
	
	public function deletecate($dbAdapter, $id)
	{
		$sql = new Sql($dbAdapter);
		$delete = $sql->delete('catepro');
		$delete->where(array('idcate'=>$id));
		$selectString = $sql->getSqlStringForSqlObject($delete);
		$results = $dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
	}
	
	public function deleteproduct($dbAdapter, $id)
	{
		$sql = new Sql($dbAdapter);
		$delete = $sql->delete('product');
		$delete->where(array('id'=>$id));
		$selectString = $sql->getSqlStringForSqlObject($delete);
		$results = $dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
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
	
	public function saveMember(Danhmuc $Danhmuc)
	{
		$data = array(
				'id'=>$Danhmuc->id,
				'name'=>$Danhmuc->name,
				'alias'=>$Danhmuc->alias,
				'order'=>$Danhmuc->order,
				'parent'=>$Danhmuc->parent,
				'cateindex'=>$Danhmuc->cateindex,
				'quantity'=>$Danhmuc->quantity,
				'description'=>$Danhmuc->description,
				'image'=>$Danhmuc->image,
				'icon'=>$Danhmuc->icon,
				'background'=>$Danhmuc->background,
				'seotitle'=>$Danhmuc->seotitle,
				'meta'=>$Danhmuc->meta,
				'keyword'=>$Danhmuc->keyword,
				'active'=>$Danhmuc->active,
		);
		$id=(int)$Danhmuc->id;
		if($id == 0)
		{
			$this->tableGateway->insert($data);
				
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