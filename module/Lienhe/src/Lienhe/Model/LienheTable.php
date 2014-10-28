<?php
namespace Lienhe\Model;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Insert;
class LienheTable
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
	
	public function getseo($dbAdapter)
	{
		$sql = new Sql($dbAdapter);
		$select = $sql->select();
		$select->from('seopage');
		$select->where(array('page'=>'Lienhe'));
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
	
		$kp = $results->current();
	
		return $kp;
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
	

	public function saveMember(Lienhe $member)
	{
		$data = array(
				'id'=>$member->id,
				'title'=>$member->title,
				'content'=>$member->content,
				'name'=>$member->name,
				'email'=>$member->email,
				'phone'=>$member->phone,
				'address'=>$member->address,
				'company'=>$member->company,
				'date'=>$member->date,
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