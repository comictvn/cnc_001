<?php
namespace Menuitem\Model;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
class MenuitemTable
{
	protected $tableGateway;
	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway=$tableGateway;
	}
	
	public function fetchAll($id)
	{
		$select = $this->tableGateway->getSql()->select()->order(array('id DESC'))->where(array('idmenu'=>$id));
		$resultSet = $this->tableGateway->selectWith($select);
		$resultSet->buffer();
		return $resultSet;
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
	
	public function saveMember(Menuitem $Menuitem)
	{
		$data = array(
				'id'=>$Menuitem->id,
				'idmenu'=>$Menuitem->idmenu,
				'name'=>$Menuitem->name,
				'alias'=>$Menuitem->alias,
				'link'=>$Menuitem->link,
				'parent'=>$Menuitem->parent,
				'order'=>$Menuitem->order,
				'icon'=>$Menuitem->icon,
				'click'=>$Menuitem->click,
				'nofollow'=>$Menuitem->nofollow,
				'active'=>$Menuitem->active,
		);
		$id=(int)$Menuitem->id;
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