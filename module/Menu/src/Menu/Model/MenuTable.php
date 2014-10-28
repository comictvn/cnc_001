<?php
namespace Menu\Model;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
class MenuTable
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
	
	public function getmenuitem($dbAdapter, $id)
	{
		$sql = new Sql($dbAdapter);
		$select = $sql->select();
		$select->from('menuitem');
		$select->where(array('idmenu'=>$id));
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$menu = array();
		for($i=0; $i<$results->count(); $i++)
		{
			$kq = $results->current();
			$menu[] = $kq;
			$results->next();
		}
		return $menu;
	}
	
	public function countitem($dbAdapter, $id)
	{
		$sql=new Sql($dbAdapter);
		$select = $sql->select();
		$select->from('menuitem');
		$select->columns(array('id'));
		$select->join('menu','menu.id=menuitem.idmenu',array('id'),$select::JOIN_INNER);
		$select->where(array('menu.id'=>$id));
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$product=array();
		for($i=0;$i<$results->count();$i++)
		{
		$kq=$results->current();
		$product[]=$kq;
		$results->next();
		}
		return count($product);
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
	
	public function saveMember(Menu $Menu)
	{
		$data = array(
				'id'=>$Menu->id,
				'name'=>$Menu->name,
				'alias'=>$Menu->alias,
				'description'=>$Menu->description,
				'active'=>$Menu->active,
		);
		$id=(int)$Menu->id;
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
	
	public function deletecontact($dbAdapter, $id)
	{
		$sql = new Sql($dbAdapter);
		$delete = $sql->delete('proinfocontact');
		$delete->where(array('idinfocontact'=>$id));
		$selectString = $sql->getSqlStringForSqlObject($delete);
		$results = $dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
	}
}