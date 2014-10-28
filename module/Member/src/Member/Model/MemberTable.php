<?php
namespace Member\Model;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;
use Member\Model\Member;
class MemberTable
{
	protected $tableGateway;
	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway=$tableGateway;
	}
	
	public function fetchAll()
	{
		$select = $this->tableGateway->getSql()->select()->where(array('role'=>'admin'))->order(array('id DESC'));
		$resultSet = $this->tableGateway->selectWith($select);
		$resultSet->buffer();
		return $resultSet;
		
	}
	
	
	public function fetchId($id)
	{
		$id=(int)$id;
		$rowset = $this->tableGateway->select(array('idTin'=>$id));
		$result = $rowset->current();
		if(!$result){
			throw new \Exception("Could not find row $id");
		}
		return $result;
	}
	
	public function fetchjob($dbAdapter)
	{
		$sql = new Sql($dbAdapter);
		$select = $sql->select();
		$select->from('music_style');
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
	
	public function saveMember(Member $member)
	{
		$data = array(
				'id'=>$member->id,
				'username'=>$member->username,
				'password'=>$member->password,
				'fullname'=>$member->fullname,
				'birthdate'=>$member->birthdate,
				'gender'=>$member->gender,
				'address'=>$member->address,
				'email'=>$member->email,
				'identitycard'=>$member->identitycard,
				'mobiphone'=>$member->mobiphone,
				'role'=>$member->role,
		);
		$id=(int)$member->id;
		if($id==0)
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