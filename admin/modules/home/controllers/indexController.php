<?php

function construct() {
//    echo "DÙng chung, load đầu tiên";
    load_model('index');
    load('lib','validation');
    load('lib','slug');
}

function indexAction() {
    load('helper','format');
    $list_users = get_list_users();
//    show_array($list_users);
    $data['list_users'] = $list_users;
    load_view('index', $data);
}


function AddPageAction() {
    load('helper','format', 'users', 'data');
    global $error, $page_title, $page_slug, $page_content, $page_status, $user_id, $upload_file, $created_at;
    $info_user = user_login();
    $data = get_user_by_username($info_user);
    $user_id = $data['user_id'];    
    if(isset($_POST['btn-add'])) {
        $error= array(); 
        
        if(empty($_POST['title'])) {
            $error['title'] = "Không được để trống tiêu đề trang";
        } else {
          
                $page_title = $_POST['title'];

        }
        
        // Kiểm tra slug
        if(empty($_POST['slug'])) {
            $error['slug'] = "Slug website không được để trống";
        } else {
     
                $page_slug = create_slug($_POST['slug']);

        }

        //Kiểm tra mô tả
        if(empty($_POST['desc'])) {
            $error['desc'] = "Không được để trống mô tả pages";
        } else {
            if(empty($_POST['desc'])) {
                $error['desc'] = 'mô tả không đúng định dạng';
            } else {
                $page_content = $_POST['desc'];
            }
        }

        if(empty($_POST['status'])) {
            $page_status = "draft";
        } else {
            $page_status = $_POST['status'];
        }

        if(isset($_FILES['file'])) {
            // show_array($_FILES);
            global $config;
            $error_images = array(); 
            $upload_dir = 'admin/public/uploads/pages';
            // Đường dẫn file sau khi upload
            $upload_file = $upload_dir.$_FILES['file']['name'];
            // Xử lý upload đúng file ảnh
            $type_allow = array('png', 'jpg', 'gift', 'jpeg');
            // pathinfo lấy đuôi file ảnh và có tham số PATHINFO_EXTENSION phía sau để lấy đuôi file
            $type = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
            if(!in_array(strtolower($type), $type_allow)) {
                $error_images['file'] = "Chỉ được upload file có đuôi png, jpg, gif, jpeg";
            }else {
                // Upload file có kích thước cho phép (<20MB ~ 29.000.000 Byte)
                $file_size = $_FILES['file']['size'];
                if($file_size > 29000000) {
                    $error_images['file'] = "chỉ được upload file bé hơn 20 MB";
                } 
                //Kiểm tra xem file đó trùng 1 file đã tồn tại trên hệ thống hay không
                if(file_exists($upload_file)) {
                    $file_name = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                    $new_file_name = $file_name . ' - Copy.';
                    $new_upload_file = $upload_dir . $new_file_name . $type;
                    $k = 1;
                    while(file_exists($new_upload_file)) {
                        $new_file_name = $file_name . " - Copy({$k}).";
                        $k++;
                        $new_upload_file = $upload_dir . $new_file_name . $type; 
                    }
                    $upload_file = $new_upload_file;
                }
            }
            if(empty($error_images)){
                if($upload_file) {
                    move_uploaded_file($_FILES['file']['tmp_name'], $upload_file);
                    // echo "<a href='$upload_file'>Download: {$_FILES['file']['name']}</a><br/>";
                    // echo "<img src='{$upload_file}'/>";
                    echo "Uploads files thành công";
                }else {
                    echo "Upload file thất bại";
                }
            }else {
                $error['file'] = $error_images;
            }
        }
       

        if(empty($error)) {
            
            if(!pages_exists($page_title, $page_slug)) {
                $time = date("d/m/Y h:m:s");
                $data = array(
                    'page_title' => $page_title,
                    'page_slug' => $page_slug,
                    'page_content' => $page_content,
                    'page_status' => $page_status,
                    'user_id' => $user_id,
                    'images_pages' => $upload_file,
                    'created_at' =>  $time,
                );
            add_pages($data);
            
            }else {
                $error['pages'] = "Đã có lỗi trong quá trình add";
            }
        } 
    }

    load_view('add');
   
}

// function detailAction() {
//     load_view('index');
// }

// function addAction() {
//     echo "Thêm dữ liệu";
// }

// function editAction() {
//     $id = (int)$_GET['id'];
//     $item = get_user_by_id($id);
//     show_array($item);
// }