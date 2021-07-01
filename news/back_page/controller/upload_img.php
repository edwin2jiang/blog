<?php
// header("Content-Type: text/html;charset=UTF-8");

/**
 * @author  Z09418208_蒋伟伟
 * @tips 成功上传,
 * - 样例数据：
 *   {
 *      "code": 0
 *      ,"msg": ""
 *      ,"data": {
 *      "src": "http://cdn.layui.com/123.jpg"
 *   }
 */

require_once '../../util/util.php';
header("Content-type: application/json;charset=UTF-8");

$photo = $_FILES['file'];
$error = $photo['error'];

$res_msg = array(
    'code' => 0,
    'msg' => '',
    'data' => array(
        'src' => ''
    )
);


$url = '';


switch ($error) {
    case 0:
        $photoName = $photo['name'];
        // echo "您的个人照片为：" . $photoName . '<br/>';
        $myPicTmpName = $photo['tmp_name'];
        // echo "临时文件名", $myPicTmpName, "<br/>";

        // 调用对应方法来保证文件名一定不会重复
        $des_name = get_filename($photoName);

        if (isset($_GET['file'])) {
            // 文件类型
            $destination = '../../uploads/files/' . $des_name;
            $url = 'uploads/files/' . $des_name;
        } else {
            // 图片类型
            $destination = '../../uploads/' . $des_name;
            $url = 'uploads/' . $des_name;
        }

        // 文件名强制转码

        if(is_windows()){
            $destination = iconv("utf-8", 'gbk', $destination);
        }


        move_uploaded_file($myPicTmpName, $destination);
        $res_msg['msg'] = "文件上传成功";
        break;
    case 1:
        $res_msg['msg'] = "文件上传超过了php.ini的限制";
        break;
    case 2:
        $res_msg['msg'] = "上传文件大小超过了Form表单中的MAX_FILE_SIZE选项的指定的值";
        break;
    case 3:
        $res_msg['msg'] = "文件只上传了部分";
        break;
    case 4:
        $res_msg['msg'] = "文件未选择";
        break;
    default:
        $res_msg['msg'] = "未知错误";
}

$res_msg['code'] = $error;
$res_msg['data']['src'] = $url;

echo json_encode($res_msg,JSON_UNESCAPED_UNICODE);
