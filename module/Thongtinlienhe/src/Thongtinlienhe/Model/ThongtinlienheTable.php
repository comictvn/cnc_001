<?php
namespace Thongtinlienhe\Model;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
class ThongtinlienheTable
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
	
	public function saveMember(Thongtinlienhe $Thongtinlienhe)
	{
		$data = array(
				'id'=>$Thongtinlienhe->id,
				'namecontact'=>$Thongtinlienhe->namecontact,
				'contactperson'=>$Thongtinlienhe->contactperson,
				'business'=>$Thongtinlienhe->business,
				'address'=>$Thongtinlienhe->address,
				'license'=>$Thongtinlienhe->license,
				'taxcode'=>$Thongtinlienhe->taxcode,
				'phone'=>$Thongtinlienhe->phone,
				'email'=>$Thongtinlienhe->email,
				'yahoo'=>$Thongtinlienhe->yahoo,
				'skype'=>$Thongtinlienhe->skype,
		);
		$id=(int)$Thongtinlienhe->id;
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