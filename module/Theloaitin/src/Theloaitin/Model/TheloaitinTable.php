<?php
namespace Theloaitin\Model;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
class TheloaitinTable
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
	
	
	public function getnewTheloaitin()
	{
		
		$rowset = $this->tableGateway->getSql()->select()->order(array('id DESC'))->limit(1);
		$resultSet = $this->tableGateway->selectWith($rowset);
		$result = $resultSet->current();
		
		return $result;
	}
	
	
	
	public function fetchTheloaitin($dbAdapter)
	{
		$sql = new Sql($dbAdapter);
		$select = $sql->select();
		$select->from('catenew');
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
		$select->from('catenew');
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
		$delete = $sql->delete('catenew');
		$delete->where(array('idcate'=>$id));
		$selectString = $sql->getSqlStringForSqlObject($delete);
		$results = $dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
	}
	
	public function deletenew($dbAdapter, $id)
	{
		$sql = new Sql($dbAdapter);
		$delete = $sql->delete('news');
		$delete->where(array('category'=>$id));
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
	
	public function saveMember(Theloaitin $Theloaitin)
	{
		$data = array(
				'id'=>$Theloaitin->id,
				'name'=>$Theloaitin->name,
				'name_en'=>$Theloaitin->name_en,
				'alias'=>$Theloaitin->alias,
				'order'=>$Theloaitin->order,
				'parent'=>$Theloaitin->parent,
				'cateindex'=>$Theloaitin->cateindex,
				'quantity'=>$Theloaitin->quantity,
				'description'=>$Theloaitin->description,
				'image'=>$Theloaitin->image,
				'icon'=>$Theloaitin->icon,
				'background'=>$Theloaitin->background,
				'seotitle'=>$Theloaitin->seotitle,
				'meta'=>$Theloaitin->meta,
				'keyword'=>$Theloaitin->keyword,
				'active'=>$Theloaitin->active,
		);
		$id=(int)$Theloaitin->id;
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