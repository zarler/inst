<?php
/**
 * Created by IntelliJ IDEA.
 * User: yangyuexin
 * Date: 2018/1/16
 * Time: 下午5:23
 */
class Model_Jinrong91_DataItem extends Model_Database{

    public function getOneByTrxno($trxno)
    {

        return $query = DB::select()->from('jinrong91_data_item')->where('trxno','=',$trxno)->execute()->current();
    }



    public function insert($array)
    {
        $_keys = array_keys($array);
        $_vals = array_values($array);

        $insert = DB::insert('jinrong91_data_item')
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

        DB::update('jinrong91_data_item')->set($update)
            ->where('id','=',$id)
            ->execute();
        return TRUE;
    }

}