<?php
namespace Slide\Model;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Insert;
class SlideTable
{
	protected $tableGateway;
	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway=$tableGateway;
	}
	
	public function fetchAll()
	{
		$select = $this->tableGateway->getSql()->select()->where('module != 2 and module = 4')->order(array('id DESC'));
		$resultSet = $this->tableGateway->selectWith($select);
		$resultSet->buffer();
		return $resultSet;
	}
	
	public function fetchAllhtml()
	{
		$select = $this->tableGateway->getSql()->select()->where('module = 2')->order(array('id DESC'));
		$resultSet = $this->tableGateway->selectWith($select);
		$resultSet->buffer();
		return $resultSet;
	}
	
	
	public function getnewBlock()
	{
		
		$rowset = $this->tableGateway->getSql()->select()->order(array('id DESC'))->limit(1);
		$resultSet = $this->tableGateway->selectWith($rowset);
		
		$result = $resultSet->current();
		
		return $result;
	}
	
	public function getslide($dbAdapter, $idblock)
	{
		$sql = new Sql($dbAdapter);
		$select = $sql->select();
		$select->from('blockcontentvalue');
		$select->join('blockcontent','blockcontent.id=blockcontentvalue.idblockcontent',array('name'),$select::JOIN_INNER);
		$select->join('block','block.id=blockcontentvalue.idblock',array('name'),$select::JOIN_INNER);
		$select->where(array('block.id'=>$idblock,'blockcontentvalue.parent'=>0));
		$select->order('blockcontentvalue.parent');
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$loai_sp = array();
		for($i = 0; $i<$results->count(); $i++)
		{
		$kp = $results->current();
		$loai_sp[]= $kp;
		$results->next();
		}
		return $loai_sp;
	}
	
	public function getslidevalue($dbAdapter, $idblock, $parent)
	{
		$sql = new Sql($dbAdapter);
		$select = $sql->select();
		$select->from('blockcontentvalue');
		$select->join('blockcontent','blockcontent.id=blockcontentvalue.idblockcontent',array('name','meta'),$select::JOIN_INNER);
		$select->join('block','block.id=blockcontentvalue.idblock',array('name'),$select::JOIN_INNER);
		$select->where("block.id = $idblock and blockcontent.meta != 'title_banner' and blockcontent.meta != 'content_banner' and blockcontentvalue.parent = $parent ");
		
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$loai_sp = array();
		for($i = 0; $i<$results->count(); $i++)
		{
		$kp = $results->current();
		$loai_sp[]= $kp;
		$results->next();
		}
		return $loai_sp;
	}
	
	public function geteditslideimage($dbAdapter, $parent)
	{
		$sql = new Sql($dbAdapter);
		$select = $sql->select();
		$select->from('blockcontentvalue');
		$select->where(array('parent'=>$parent));
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$loai_sp = array();
		for($i = 0; $i<$results->count(); $i++)
		{
		$kp = $results->current();
		$loai_sp[]= $kp;
		$results->next();
		}
		return $loai_sp;
	}
	
	public function geteditslideimage_name($dbAdapter, $parent)
	{
		$sql = new Sql($dbAdapter);
		$select = $sql->select();
		$select->from('blockcontentvalue');
		$select->where(array('id'=>$parent));
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$loai_sp = array();
		for($i = 0; $i<$results->count(); $i++)
		{
		$kp = $results->current();
		$loai_sp[]= $kp;
		$results->next();
		}
		return $loai_sp;
	}
	
	public function getposition($dbAdapter)
	{
		$sql = new Sql($dbAdapter);
		$select = $sql->select();
		$select->from('position');
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$loai_sp = array();
		for($i = 0; $i<$results->count(); $i++)
		{
		$kp = $results->current();
		$loai_sp[]= $kp;
				$results->next();
		}
		return $loai_sp;
	}
	
	public function getposition1($dbAdapter)
	{
		$sql = new Sql($dbAdapter);
		$select = $sql->select();
		$select->from('position');
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$loai_sp = array();
		for($i = 0; $i<$results->count(); $i++)
		{
		$kp = $results->current();
		$loai_sp[$kp['alias']]= $kp['name'];
		$results->next();
		}
		return $loai_sp;
	}
	
	public function getpage($dbAdapter)
	{
		$sql = new Sql($dbAdapter);
		$select = $sql->select();
		$select->from('page');
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$loai_sp = array();
		for($i = 0; $i<$results->count(); $i++)
		{
		$kp = $results->current();
		$loai_sp[]= $kp;
		$results->next();
		}
		return $loai_sp;
	}
	
	public function getpageblock($dbAdapter)
	{
		$sql = new Sql($dbAdapter);
		$select = $sql->select();
		$select->from('pageblock');
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$loai_sp = array();
		for($i = 0; $i<$results->count(); $i++)
		{
		$kp = $results->current();
		$loai_sp[]= $kp;
		$results->next();
		}
		return $loai_sp;
	}
	
	public function getblockcontent($dbAdapter)
	{
		$sql = new Sql($dbAdapter);
		$select = $sql->select();
		$select->from('blockcontentvalue');
		$select->join('blockcontent','blockcontent.id=blockcontentvalue.idblockcontent',array('name'),$select::JOIN_INNER);
		$select->where(array('parent'=>0));
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$loai_sp = array();
		for($i = 0; $i<$results->count(); $i++)
		{
		$kp = $results->current();
		$loai_sp[]= $kp;
		$results->next();
		}
		return $loai_sp;
	}
	
	public function getblockcontent0($dbAdapter)
	{
		$sql = new Sql($dbAdapter);
		$select = $sql->select();
		$select->from('blockcontentvalue');
		$select->join('blockcontent','blockcontent.id=blockcontentvalue.idblockcontent',array('name'),$select::JOIN_INNER);
		$select->where('parent != 0');
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$loai_sp = array();
		for($i = 0; $i<$results->count(); $i++)
		{
		$kp = $results->current();
		$loai_sp[]= $kp;
		$results->next();
		}
		return $loai_sp;
	}
	
	public function insertblockpage($dbAdapter, $data)
	{
		$sql = new Sql($dbAdapter);
		$insert = $sql->insert('pageblock');
		$insert->values($data);
		$selectString = $sql->getSqlStringForSqlObject($insert);
		$results = $dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
	}
	
	
	
	public function insertblockcontent($dbAdapter, $data)
	{
		$objInsert = new Insert('blockcontentvalue');
		$objInsert->values($data);
		$sql = new Sql($dbAdapter);
		return $result = $sql->prepareStatementForSqlObject($objInsert)->execute()->getGeneratedValue();
	}
	
	public function deleteblockcontent($dbAdapter, $data)
	{
		$sql = new Sql($dbAdapter);
		$delete = $sql->delete('blockcontentvalue');
		$delete->where(array('idblock'=>$data['idblock'], 'idblockcontent'=>$data['idblockcontent']));
		$selectString = $sql->getSqlStringForSqlObject($delete);
		$results = $dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
	}
	
	public function deleteimageslide($dbAdapter, $parent)
	{
		$sql = new Sql($dbAdapter);
		$delete_1 = $sql->delete('blockcontentvalue');
		$delete_1->where(array('parent'=>$parent));
		$selectString_1 = $sql->getSqlStringForSqlObject($delete_1);
		$results = $dbAdapter->query($selectString_1, Adapter::QUERY_MODE_EXECUTE);
		
		$delete_2 = $sql->delete('blockcontentvalue');
		$delete_2->where(array('id'=>$parent));
		$selectString_2 = $sql->getSqlStringForSqlObject($delete_2);
		$results = $dbAdapter->query($selectString_2, Adapter::QUERY_MODE_EXECUTE);
	}
	
	public function deleteblockpage($dbAdapter, $data)
	{
		$sql = new Sql($dbAdapter);
		$delete = $sql->delete('pageblock');
		$delete->where(array('idblock'=>$data['idblock']));
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
	
	public function saveMember(Slide $Slide)
	{
		$data = array(
				'id'=>$Slide->id,
				'module'=>$Slide->module,
				'position'=>$Slide->position,
				'name'=>$Slide->name,
				'html'=>$Slide->html,
				'css'=>$Slide->css,
				'link'=>$Slide->link,
				'active'=>$Slide->active,
				
		);
		$id=(int)$Slide->id;
		if($id == 0)
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