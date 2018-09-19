config/redis.php copy application/config

支持 Session
支持 多组redis server
支持 字符串KEY-VALUE
支持 哈希表HASHTABLE

例子:
    $add1 = Redis_Hash::instance()->set('h1',array('key3'=>'kkk1','key4'=>'kkkk'));
    $row1 = Redis_Hash::instance()->get('h1');
    $del1 = Redis_Hash::instance()->del_field('h1','key3');
    $has1 = Redis_Hash::instance()->exists_field('h1','key4');
    $data1 = Redis_Hash::instance()->get('h1');
    $field1 = Redis_Hash::instance()->get_field('h1',array('key3','key4'));

    $me = Redis_String::instance('default')->get('name');
    $me = Redis_String::instance('default')->set('name','majin');
    $me = Redis_String::instance('default')->strlen('name');

