<?php
namespace Application\Model;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;
class ApplicationTable
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
	
	
	public function getblock($dbAdapter)
	{
		$sql = new Sql($dbAdapter);
		$select = $sql->select();
		$select->from('block');
		$select->where(array('active'=>1));
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$loai_sp = array();
		for($i = 0; $i<$results->count(); $i++)
		{
		$kp = $results->current();
		$loai_sp[]=$kp;
				$results->next();
		}
		return $loai_sp;
	}
	
	public function baivietnoibat($dbAdapter)
	{
		$sql = new Sql($dbAdapter);
		$select = $sql->select();
		$select->from('news');
		$select->where(array('active'=>1,'index'=>1));
                $select->order('id DESC');
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$loai_sp = array();
		for($i = 0; $i<$results->count(); $i++)
		{
		$kp = $results->current();
		$loai_sp[]=$kp;
		$results->next();
		}
		return $loai_sp;
	}
	
	public function getseo($dbAdapter)
	{
		$sql = new Sql($dbAdapter);
		$select = $sql->select();
		$select->from('seopage');
		$select->where(array('page'=>'Application'));
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		
		$kp = $results->current();
		
		return $kp;
	}
	
	public function getproductnew($dbAdapter)
	{
		$sql = new Sql($dbAdapter);
		$select = $sql->select();
		$select->from('product');
		$select->where(array('pronew'=>1));
		$select->order('id DESC');
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$loai_sp = array();
		for($i = 0; $i<$results->count(); $i++)
		{
		$kp = $results->current();
		$loai_sp[]=$kp;
		$results->next();
		}
		return $loai_sp;
	}

	public function getsanphamnoibat($dbAdapter)
	{
		$sql = new Sql($dbAdapter);
		$select = $sql->select();
		$select->from('product');
		$select->where(array('proselling'=>1));
		$select->order('id DESC');
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$loai_sp = array();
		for($i = 0; $i<$results->count(); $i++)
		{
		$kp = $results->current();
		$loai_sp[]=$kp;
		$results->next();
		}
		return $loai_sp;
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
	
	
}