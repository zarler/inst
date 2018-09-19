<?php
/**
 * Created by IntelliJ IDEA.
 * User: yangyuexin
 * Date: 2018/1/16
 * Time: 下午5:23
 */
class Model_Jinrong91_Data extends Model_Database{

    public function getOneByTrxno($trxno)
    {

        return $query = DB::select()->from('jinrong91_data')->where('trxno','=',$trxno)->execute()->current();
    }
    public function getOneByUserId($user_id)
    {

        $result = DB::select()->from('jinrong91_data')->where('user_id','=',$user_id)->order_by('id', 'DESC')->execute()->current();
        if($result&&count($result)>0){
           return $result;
        }else{
            return false;
        }
    }
    public function getOneByAction($user_id,$action)
    {

        $result = DB::select()->from('jinrong91_data')->where('user_id','=',$user_id)->and_where('action','=',$action)->order_by('id', 'DESC')->execute()->current();
        if($result&&count($result)>0){
            return $result;
        }else{
            return false;
        }
    }

    public function insert($array)
    {
        $_keys = array_keys($array);
        $_vals = array_values($array);

        $insert = DB::insert('jinrong91_data')
            ->columns($_keys)
            ->values($_vals);

        list($insert_id, $affected_rows) = $insert->execute();
        return $insert_id;

    }
    public function update($id,$array){
        if($id < 0){
            return FALSE;
        }
        $update = $array;

        DB::update('jinrong91_data')->set($update)
            ->where('id','=',$id)
            ->execute();
        return TRUE;
    }

}