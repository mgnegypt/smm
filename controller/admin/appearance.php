<?php

  if( !route(2) ):
    $route[2]   = "pages";
  endif;

  if( $_SESSION["client"]["data"] ):
    $data = $_SESSION["client"]["data"];
    foreach ($data as $key => $value) {
      $$key = $value;
    }
    unset($_SESSION["client"]);
  endif;

  $menuList = [
    $languageArray["admin.appearance.menu.pages"] ?? "Pages" => "pages",
    $languageArray["admin.appearance.menu.announcements"] ?? "Announcements" => "news",
    $languageArray["admin.appearance.menu.blog"] ?? "Blog" => "blog",
    $languageArray["admin.appearance.menu.menu"] ?? "Menu" => "menu",
    $languageArray["admin.appearance.menu.themes"] ?? "Themes" => "themes",
    $languageArray["admin.appearance.menu.languages"] ?? "Languages" => "language",
    $languageArray["admin.appearance.menu.folders"] ?? "Folders" => "files"
  ];

  if( !array_search(route(2),$menuList) ):
    header("Location:".site_url("admin/appearance"));
  
   elseif( route(2) == "pages" ):
    $access = $user["access"]["pages"];
      if( $access ):
        if( route(3) == "edit" ):
            $title = "Pages";
          if( $_POST ):
            $id = route(4);
            foreach ($_POST as $key => $value) {
              $$key = $value;
            }
              if( $content == "<br>" || $content == "<p><br></p>" ): $content = ""; endif;
            if( !countRow(["table"=>"pages","where"=>["page_get"=>$id]]) ):
              $error    = 1;
              $icon     = "error";
              $errorText= "Please choose valid payment method";
            else:
              $update = $conn->prepare("UPDATE pages SET page_content=:content WHERE page_get=:id ");
              $update->execute(array("id"=>$id,"content"=>$content ));
                if( $update ):
                  $success    = 1;
                  $successText= "Transaction successful";
                else:
                  $error    = 1;
                  $errorText= "Operation failed";
                endif;
            endif;
          endif;
          $page = $conn->prepare("SELECT * FROM pages WHERE page_get=:get ");
          $page->execute(array("get"=>route(4)));
          $page = $page->fetch(PDO::FETCH_ASSOC); if( !$page ): header("Location:".site_url("admin/appearance/pages")); endif;
        elseif( !route(3) ):
          $pageList = $conn->prepare("SELECT * FROM pages ");
          $pageList->execute(array());
          $pageList = $pageList->fetchAll(PDO::FETCH_ASSOC);
        else:
          header("Location:".site_url("admin/appearance/pages"));
        endif;
      endif;
    if( route(5) ): header("Location:".site_url("admin/appearance/pages")); endif;
    
      elseif( route(2) == "menu" ):
          
    $access = $user["access"]["menu"];
    
          if( $access ):
              
               $id = route(4);
         
        if( $id ):
            
          if(route(3) == "public_true"):

          $update = $conn->prepare("UPDATE menu SET status=:status WHERE id=:id");
          $update = $update->execute(array("id"=>$id,"status"=>2));
          
          header("Location:".site_url("admin/appearance/menu"));

          
          elseif(route(3) == "public_false"):

          $update = $conn->prepare("UPDATE menu SET status=:status WHERE id=:id");
          $update = $update->execute(array("id"=>$id,"status"=>1));
           
          header("Location:".site_url("admin/appearance/menu"));

          
          ## Burası yangın yeri ##
          
          elseif(route(3) == "nopublic_true"): 

              $update = $conn->prepare("UPDATE menu SET public=:public WHERE id=:id");
              $update = $update->execute(array("id"=>$id,"public"=>2));
              
                        header("Location:".site_url("admin/appearance/menu"));

              
          elseif(route(3) == "nopublic_false"): 

              $update = $conn->prepare("UPDATE menu SET public=:public WHERE id=:id");
              $update = $update->execute(array("id"=>$id,"public"=>1));
                    header("Location:".site_url("admin/appearance/menu"));

          endif;

  

        endif;
              
              
        $public = $conn->prepare("SELECT * FROM menu WHERE menu.edit=:edit");
        $public->execute(array("edit"=>0));
        $public = $public->fetchAll(PDO::FETCH_ASSOC);
        
        $nopublic = $conn->prepare("SELECT * FROM menu WHERE menu.edit=:edit");
        $nopublic->execute(array("edit"=>0));
        $nopublic = $nopublic->fetchAll(PDO::FETCH_ASSOC);
              
        if( $_POST ):
          foreach ($_POST as $key => $value) {
            $$key = $value;
          }
          $conn->beginTransaction();
          $update = $conn->prepare("UPDATE settings SET service_list=:services  WHERE id=:id ");
          $update = $update->execute(array("id"=>1,"services"=>$services  ));
          if( $update ):
            $conn->commit();
            header("Location:".site_url("admin/appearance/menu"));
            $_SESSION["client"]["data"]["success"]    = 1;
            $_SESSION["client"]["data"]["successText"]= "Transaction successful";
          else:
            $conn->rollBack();
            $error    = 1;
            $errorText= "Operation failed";
          endif;
          
        endif;
      endif;

    
    elseif( route(2) == "blog" ):
      
      $titleAdmin = "Blog";
    $access = $user["access"]["blog"];
      if( $access ):
 function permalink($str, $options = array())
 {
     $str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());
     $defaults = array(
         'delimiter' => '-',
         'limit' => null,
         'lowercase' => true,
         'replacements' => array(),
         'transliterate' => true
     );
     $options = array_merge($defaults, $options);
     $char_map = array(
         // Latin
         'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C',
         'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
         'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O',
         'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH',
         'ß' => 'ss',
         'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c',
         'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
         'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o',
         'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th',
         'ÿ' => 'y',
         // Latin symbols
         '©' => '(c)',
         // Greek
         'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
         'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
         'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
         'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
         'Ϋ' => 'Y',
         'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
         'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
         'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
         'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
         'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',
         // Turkish
         'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
         'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g',
         // Russian
         'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
         'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
         'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
         'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
         'Я' => 'Ya',
         'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
         'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
         'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
         'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
         'я' => 'ya',
         // Ukrainian
         'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
         'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',
         // Czech
         'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U',
         'Ž' => 'Z',
         'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
         'ž' => 'z',
         // Polish
         'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z',
         'Ż' => 'Z',
         'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
         'ż' => 'z',
         // Latvian
         'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N',
         'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
         'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
         'š' => 's', 'ū' => 'u', 'ž' => 'z'
     );
     $str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);
     if ($options['transliterate']) {
         $str = str_replace(array_keys($char_map), $char_map, $str);
     }
     $str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);
     $str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);
     $str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');
     $str = trim($str, $options['delimiter']);
     return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
 }
 
        if( route(3) == "edit" ):
          if( $_POST ):
            $id = route(4);
            foreach ($_POST as $key => $value) {
              $$key = $value;
            }
        
          if ( $_FILES["logo"] && ( $_FILES["logo"]["type"] == "image/jpeg" || $_FILES["logo"]["type"] == "image/jpg" || $_FILES["logo"]["type"] == "image/png" || $_FILES["logo"]["type"] == "image/gif"  ) ):
            $logo_name      = $_FILES["logo"]["name"];
            $uzanti         = substr($logo_name,-4,4);
            $logo_newname   = "images/blog/".md5(rand(10,999)).".jpg";
            $upload_logo    = move_uploaded_file($_FILES["logo"]["tmp_name"],$logo_newname);
            
          elseif( $post["blog_image"] != "" ):
            $logo_newname   = $post["blog_image"];
          else:
            $logo_newname   = "";
          endif;
        
       if( empty($content) ):
          $error    = 1;
          $errorText= "Please write a blog";
          $icon     = "error";
        elseif( empty($name) ):
          $error    = 1;
          $errorText= "Write name";
          $icon     = "error";
        else:
              $update = $conn->prepare("UPDATE blogs SET blog_content=:content, blog_title=:name, blog_image=:logo WHERE id=:id ");
              $update->execute(array("id"=>$id,"content"=>$content,"name"=>$name,"logo"=>$logo_newname ));
                if( $update ):
                  $success    = 1;
                  $successText= "Transaction successful";
                else:
                  $error    = 1;
                  $errorText= "Operation failed";
                endif;
            endif;
          endif;
          $post = $conn->prepare("SELECT * FROM blogs WHERE id=:id ORDER BY blog_created DESC ");
          $post->execute(array("id"=>route(4)));
          $post = $post->fetch(PDO::FETCH_ASSOC); if( !$post ): header("Location:".site_url("admin/appearance/blog")); 
        endif;

        elseif( !route(3) ):
            
            
              if( $_POST ):
        
            foreach ($_POST as $key => $value) {
              $$key = $value;
            }
        
          if ( $_FILES["logo"] && ( $_FILES["logo"]["type"] == "image/jpeg" || $_FILES["logo"]["type"] == "image/jpg" || $_FILES["logo"]["type"] == "image/png" || $_FILES["logo"]["type"] == "image/gif"  ) ):
            $logo_name      = $_FILES["logo"]["name"];
            $uzanti         = substr($logo_name,-4,4);
            $logo_newname   = "images/blog/".md5(rand(10,999)).".jpg";
            $upload_logo    = move_uploaded_file($_FILES["logo"]["tmp_name"],$logo_newname);
            
          elseif( $post["blog_image"] != "" ):
            $logo_newname   = $post["blog_image"];
          else:
            $logo_newname   = "";
          endif;
        
       if( empty($content) ):
          $error    = 1;
          $errorText= "Please write a blog";
          $icon     = "error";
        elseif( empty($name) ):
          $error    = 1;
          $errorText= "Write name";
          $icon     = "error";
        else:
            

          $insert = $conn->prepare("INSERT INTO blogs SET blog_content=:content, blog_title=:name, blog_image=:logo, blog_created=:date, url=:url ");
          $insert = $insert->execute(array("content"=>$content,"name"=>$name,"logo"=>$logo_newname,"date"=>date("Y-m-d H:i:s"),"url"=>permalink($name) ));
              
                if( $insert ):
                  $success    = 1;
                  $successText= "Transaction successful";
                  $referrer = site_url("admin/settings/blog");
                else:
                  $error    = 1;
                  $errorText= "Operation failed";
                endif;
            endif;
          endif;
     
       
            
          $postList = $conn->prepare("SELECT * FROM blogs ORDER BY blog_created DESC ");
          $postList->execute(array());
          $postList = $postList->fetchAll(PDO::FETCH_ASSOC);
          
          elseif( route(3) == "delete" ):
          $id = route(4);
            if( !countRow(["table"=>"blogs","where"=>["id"=>$id]]) ):
              $error    = 1;
              $icon     = "error";
              $errorText= "Please select valid payout bonus";
            else:
              $delete = $conn->prepare("DELETE FROM blogs WHERE id=:id ");
              $delete->execute(array("id"=>$id));
      
                if( $delete ):
                  $error    = 1;
                  $icon     = "success";
                  $errorText= "Transaction successful";
                  $referrer = site_url("admin/settings/blog");
                else:
                  $error    = 1;
                  $icon     = "error";
                  $errorText= "Operation failed";
                endif;
            endif;
             header("Location:".site_url("admin/appearance/blog"));
            exit();
        else:
          header("Location:".site_url("admin/appearance/blog"));
        endif;
      endif;
    if( route(5) ): header("Location:".site_url("admin/appearance/blog")); endif;
    
    
  elseif( route(2) == "language" ):
      $titleAdmin = "Language";
    $access = $user["access"]["language"];
      if( $access ):
        $languageList = $conn->prepare("SELECT * FROM languages");
        $languageList->execute(array());
        $languageList = $languageList->fetchAll(PDO::FETCH_ASSOC);
        if( route(3) && route(3) != "new" && !countRow(["table"=>"languages","where"=>["language_code"=>route(3)]]) ):
          header("Location:".site_url("admin/appearance/language"));
        elseif( route(3) == "new" ):
          include 'language/default.php';
        else:
            if(route(3)){
          $language = $conn->prepare("SELECT * FROM languages WHERE language_code=:code");
          $language->execute(array("code"=>route(3)));
          $language = $language->fetch(PDO::FETCH_ASSOC);
          include 'language/'.route(3).'.php';
         }
        endif;
        if( $_POST && route(3) != "new" && countRow(["table"=>"languages","where"=>["language_code"=>route(3)]]) ):
            
        $isim = $_POST["language"];
            
          $update = $conn->prepare("UPDATE languages SET language_name=:name WHERE language_code=:code ");
          $update->execute(array("code"=>route(3),"name"=>$isim));
            
          $html = '<?php '.PHP_EOL.PHP_EOL;
          $html.= '$languageArray= [';
          foreach ($_POST["Language"] as $key => $value):

            $value = str_replace('"',"'",$value);

            $html .= ' "'.$key.'" => "'.$value.'", '.PHP_EOL;
          endforeach;
          $html .=  '];';
          file_put_contents('language/'.route(3).'.php', $html);
          header("Location:".site_url("admin/appearance/language/".route(3)));
        elseif( route(3) == "new" && $_POST ):
          $name = $_POST["language"];
          $code = $_POST["languagecode"];
          if( countRow(["table"=>"languages","where"=>["language_code"=>$code]]) ):
            $error      = 1;
            $errorText  = "Bu dil kodu zaten kullanılıyor.";
          else:
            $insert = $conn->prepare("INSERT INTO languages SET language_name=:name, language_code=:code ");
            $insert->execute(array("name"=>$name,"code"=>$code ));
              if( $insert ):
                $html = '<?php '.PHP_EOL.PHP_EOL;
                $html.= '$languageArray= [';
                foreach ($_POST["Language"] as $key => $value):
                  $value = str_replace('"',"'",$value);

                  $html .= ' "'.$key.'" => "'.$value.'", '.PHP_EOL;
                endforeach;
                $html .=  '];';
                file_put_contents('language/'.$code.'.php', $html);
                header("Location:".site_url("admin/appearance/language/"));
              endif;
          endif;
        elseif( $_GET["lang-default"] && $_GET["lang-id"] ):
          $update = $conn->prepare("UPDATE languages SET default_language=:default");
          $update->execute(array("default"=>0));
          $update = $conn->prepare("UPDATE languages SET default_language=:default WHERE language_code=:code ");
          $update->execute(array("code"=>$_GET["lang-id"],"default"=>1));
          header("Location:".site_url("admin/appearance/language"));
        elseif( $_GET["lang-type"] && $_GET["lang-id"] ):
          if( countRow(["table"=>"languages","where"=>["language_type"=>"2"]]) > 1 && $_GET["lang-type"] == 1 ):
            $update = $conn->prepare("UPDATE languages SET language_type=:type WHERE language_code=:code ");
            $update->execute(array("code"=>$_GET["lang-id"],"type"=>$_GET["lang-type"]));
          elseif( $_GET["lang-type"] == 2 ):
            $update = $conn->prepare("UPDATE languages SET language_type=:type WHERE language_code=:code ");
            $update->execute(array("code"=>$_GET["lang-id"],"type"=>$_GET["lang-type"]));
          endif;
          header("Location:".site_url("admin/appearance/language"));
        endif;
      endif;
  elseif( route(2) == "themes" ):
      $titleAdmin = "Themes";
    $access = $user["access"]["themes"];
      if( $access ):
        $conn->exec("CREATE TABLE IF NOT EXISTS theme_settings (
          id INT(11) NOT NULL AUTO_INCREMENT,
          theme_dirname VARCHAR(120) NOT NULL,
          token_primary VARCHAR(20) NOT NULL DEFAULT '#73a7ff',
          card_style ENUM('soft','sharp','glass') NOT NULL DEFAULT 'glass',
          ui_density ENUM('compact','comfortable','spacious') NOT NULL DEFAULT 'comfortable',
          token_payload TEXT NULL,
          updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (id),
          UNIQUE KEY uniq_theme_dirname (theme_dirname)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8");

        if( route(3) == "preview" && countRow(["table"=>"themes","where"=>["theme_dirname"=>route(4)]]) ):
          $_SESSION["theme_preview"] = [
            "theme" => route(4),
            "expires_at" => time() + 1800,
            "by" => (int) $user["client_id"]
          ];
          $_SESSION["theme"] = route(4);
          header("Location:".site_url()."?theme-preview=1");
          exit;
        elseif( route(3) == "clear-preview" ):
          unset($_SESSION["theme_preview"]);
          unset($_SESSION["theme"]);
          header("Location:".site_url("admin/appearance/themes"));
          exit;
        elseif( route(3) == "active" && countRow(["table"=>"themes","where"=>["theme_dirname"=>route(4)]]) ):
          $update = $conn->prepare("UPDATE settings SET site_theme=:theme WHERE id=:id ");
          $update->execute(array("id"=>1,"theme"=>route(4)));
          
          unset($_SESSION["theme_preview"]);
          unset($_SESSION["theme"]);
          
          header("Location:".site_url("admin/appearance/themes"));
        elseif( route(3) && countRow(["table"=>"themes","where"=>["theme_dirname"=>route(3)]]) ):
          $lyt   =  $_GET["file"];
          $theme = $conn->prepare("SELECT * FROM themes WHERE theme_dirname=:name");
          $theme->execute(array("name"=>route(3)));
          $theme = $theme->fetch(PDO::FETCH_ASSOC);
          $themeExtras = json_decode($theme["theme_extras"], true);
          if( !is_array($themeExtras) ): $themeExtras = []; endif;
          $themeMetadata = isset($themeExtras["metadata"]) && is_array($themeExtras["metadata"]) ? $themeExtras["metadata"] : [];

          $themeSetting = $conn->prepare("SELECT * FROM theme_settings WHERE theme_dirname=:theme LIMIT 1");
          $themeSetting->execute(["theme" => $theme["theme_dirname"]]);
          $themeSetting = $themeSetting->fetch(PDO::FETCH_ASSOC);

          if( !$themeSetting ):
            $defaultSetting = $conn->prepare("INSERT INTO theme_settings (theme_dirname) VALUES (:theme)");
            $defaultSetting->execute(["theme" => $theme["theme_dirname"]]);
            $themeSetting = [
              "theme_dirname" => $theme["theme_dirname"],
              "token_primary" => "#73a7ff",
              "card_style" => "glass",
              "ui_density" => "comfortable"
            ];
          endif;
            if( substr($lyt, -3) == "css"  ){
              $fn       = "css/panel/".$theme["theme_dirname"]."/".$lyt;
              $codeType = "css";
              $dir      = "CSS";
            }elseif( substr($lyt, -2) == "js"  ){
              $fn       = "js/panel/".$theme["theme_dirname"]."/".$lyt;
              $codeType = "js";
              $dir      = "JS";
            }else{
              $fn       = "themes/panel/".$theme["theme_dirname"]."/".$lyt;
              $codeType = "twig";
              $dir      = "HTML";
            }
          if( $_POST ):
            $action = $_POST["action"] ?? "update_code";
            if( $action == "save_theme_metadata" ):
              $themeMetadata = [
                "name" => trim($_POST["meta_name"] ?? $theme["theme_name"]),
                "version" => trim($_POST["meta_version"] ?? "1.0.0"),
                "author" => trim($_POST["meta_author"] ?? "Unknown"),
                "settings_schema" => [
                  "token_primary" => "color",
                  "card_style" => ["soft","sharp","glass"],
                  "ui_density" => ["compact","comfortable","spacious"]
                ]
              ];
              $themeExtras["metadata"] = $themeMetadata;
              $updateThemeMeta = $conn->prepare("UPDATE themes SET theme_name=:name, theme_extras=:extras WHERE id=:id");
              $updateThemeMeta->execute([
                "id" => $theme["id"],
                "name" => $themeMetadata["name"],
                "extras" => json_encode($themeExtras, JSON_UNESCAPED_UNICODE)
              ]);
              header("Location:".site_url("admin/appearance/themes/".$theme["theme_dirname"]));
              exit;
            elseif( $action == "save_theme_settings" ):
              $tokenPrimary = trim($_POST["token_primary"] ?? "#73a7ff");
              $cardStyle = $_POST["card_style"] ?? "glass";
              $uiDensity = $_POST["ui_density"] ?? "comfortable";
              if( !preg_match('/^#[a-fA-F0-9]{6}$/', $tokenPrimary) ): $tokenPrimary = "#73a7ff"; endif;
              if( !in_array($cardStyle,["soft","sharp","glass"]) ): $cardStyle = "glass"; endif;
              if( !in_array($uiDensity,["compact","comfortable","spacious"]) ): $uiDensity = "comfortable"; endif;
              $tokenPayload = json_encode([
                "token_primary" => $tokenPrimary,
                "card_style" => $cardStyle,
                "ui_density" => $uiDensity
              ], JSON_UNESCAPED_UNICODE);

              $saveThemeSettings = $conn->prepare("INSERT INTO theme_settings (theme_dirname, token_primary, card_style, ui_density, token_payload) VALUES (:theme,:primary,:card,:density,:payload) ON DUPLICATE KEY UPDATE token_primary=VALUES(token_primary), card_style=VALUES(card_style), ui_density=VALUES(ui_density), token_payload=VALUES(token_payload)");
              $saveThemeSettings->execute([
                "theme" => $theme["theme_dirname"],
                "primary" => $tokenPrimary,
                "card" => $cardStyle,
                "density" => $uiDensity,
                "payload" => $tokenPayload
              ]);
              header("Location:".site_url("admin/appearance/themes/".$theme["theme_dirname"]));
              exit;
            elseif( $lyt ):
              $text = $_POST["code"];
              $text = str_replace("&lt;","<",$text);
              $text = str_replace("&gt;",">",$text);
              $text = str_replace("&quot;",'"',$text);
              $updated_file   = fopen($fn,"w");
              fwrite($updated_file, $text);
              fclose($updated_file);
              header("Location:".site_url("admin/appearance/themes/".$theme["theme_dirname"]."?file=".$lyt));
              exit;
            endif;
          endif;
        elseif( route(3) && !countRow(["table"=>"themes","where"=>["theme_dirname"=>route(3)]]) ):
          header("Location:".site_url("admin/appearance/themes"));
        else:
          $themes = $conn->prepare("SELECT * FROM themes ORDER BY id DESC");
          $themes->execute(array());
          $themes = $themes->fetchAll(PDO::FETCH_ASSOC);
          foreach($themes as $index => $listedTheme):
            $listedThemeExtras = json_decode($listedTheme["theme_extras"], true);
            $listedMetadata = isset($listedThemeExtras["metadata"]) && is_array($listedThemeExtras["metadata"]) ? $listedThemeExtras["metadata"] : [];
            $themes[$index]["meta_version"] = $listedMetadata["version"] ?? "1.0.0";
            $themes[$index]["meta_author"] = $listedMetadata["author"] ?? "Unknown";
          endforeach;
        endif;
      endif;


 elseif( route(2) == "news" ):

    $access = $user["access"]["providers"];
      if( $access ):
          
        if( route(3) == "new" && $_POST ):
          foreach ($_POST as $key => $value) {
            $$key = $value;
          }

          if( empty($icon) ):
            $error    = 1;
            $errorText= "Select icon.";
            $icon     = "error";
          elseif( empty($title) ):
            $error    = 1;
            $errorText= "Announcement name cannot be empty";
            $icon     = "error";
          elseif( empty($content) ):
            $error    = 1;
            $errorText= "Announcement content cannot be empty";
            $icon     = "error";
          else:
              
            $conn->beginTransaction();
            $insert = $conn->prepare("INSERT INTO news SET news_icon=:icon, news_title=:title, news_content=:content, news_date=:date ");
            $insert = $insert->execute(array("icon"=>$icon,"title"=>$title,"content"=>$content,"date"=>date("Y-m-d H:i:s") ));
            if( $insert ):
              $conn->commit();
              $referrer = site_url("admin/appearance/news");
              $error    = 1;
              $errorText= "Transaction successful";
              $icon     = "success";
            else:
              $conn->rollBack();
              $error    = 1;
              $errorText= "Operation failed";
              $icon     = "error";
            endif;
          endif;
          echo json_encode(["t"=>"error","m"=>$errorText,"s"=>$icon,"r"=>$referrer,"time"=>1]);
          exit();
        elseif( route(3) == "edit" && $_POST  ):
          foreach ($_POST as $key => $value) {
            $$key = $value;
          }
          $id = route(4);
         
          if( empty($icon) ):
            $error    = 1;
            $errorText= "Select icon.";
            $icon     = "error";
          elseif( empty($title) ):
            $error    = 1;
            $errorText= "Announcement name cannot be empty";
            $icon     = "error";
          elseif( empty($content) ):
            $error    = 1;
            $errorText= "Announcement content cannot be empty";
            $icon     = "error";
          else:
   
            $conn->beginTransaction();
            $update = $conn->prepare("UPDATE news SET news_icon=:icon, news_title=:title, news_content=:content WHERE id=:id ");
            $update = $update->execute(array("icon"=>$icon,"title"=>$title,"content"=>$content,"id"=>$id));
            if( $update ):
              $conn->commit();
              $referrer = site_url("admin/appearance/news");
              $error    = 1;
              $errorText= "Transaction successful";
              $icon     = "success";
            else:
              $conn->rollBack();
              $error    = 1;
              $errorText= "Operation failed";
              $icon     = "error";
            endif;
          endif;
          echo json_encode(["t"=>"error","m"=>$errorText,"s"=>$icon,"r"=>$referrer,"time"=>1]);
          exit();
              elseif( route(3) == "delete" ):
          $id = route(4);
            if( !countRow(["table"=>"news","where"=>["id"=>$id]]) ):
              $error    = 1;
              $icon     = "error";
              $errorText= "Please select valid announcement";
            else:
              $delete = $conn->prepare("DELETE FROM news WHERE id=:id ");
              $delete->execute(array("id"=>$id));
                if( $delete ):
                  $error    = 1;
                  $icon     = "success";
                  $errorText= "Transaction successful";
                  $referrer = site_url("admin/appearance/news");
                else:
                  $error    = 1;
                  $icon     = "error";
                  $errorText= "Operation failed";
                endif;
            endif;
            echo json_encode(["t"=>"error","m"=>$errorText,"s"=>$icon,"r"=>$referrer,"time"=>0]);
            exit();
        elseif( !route(3) ):
          $newsList = $conn->prepare("SELECT * FROM news ");
          $newsList->execute(array());
          $newsList = $newsList->fetchAll(PDO::FETCH_ASSOC);
        else:
          header("Location:".site_url("admin/appearance/news"));
        endif;
      endif;
      if( route(5) ): header("Location:".site_url("admin/appearance/news")); endif;

      elseif( route(2) == "files" ):
     
      $access = $user["access"]["blog"];
        if( $access ):            
        
                if($_FILES["logo"] ):
               
               if ( $_FILES["logo"] && ( $_FILES["logo"]["type"] == "image/jpeg" || $_FILES["logo"]["type"] == "image/jpg" || $_FILES["logo"]["type"] == "image/png" || $_FILES["logo"]["type"] == "image/gif"  ) ):
            $logo_name      = $_FILES["logo"]["name"];
            $uzanti         = substr($logo_name,-4,4);
            $logo_newname   = "img/files/".md5(rand(1,999999)).$uzanti;
            $upload_logo    = move_uploaded_file($_FILES["logo"]["tmp_name"],$logo_newname);
               
                $url = site_url($logo_newname);
             
                $insert = $conn->prepare("INSERT INTO files SET link=:link, date=:date");      
                $insert = $insert->execute(array("link"=>$url,"date"=>date("Y-m-d H:i:s")));
                 
          endif;
          
                endif;
        
                $fileList = $conn->prepare("SELECT * FROM files ORDER BY date DESC ");
                $fileList->execute(array());
                $fileList = $fileList->fetchAll(PDO::FETCH_ASSOC);
                
                //1
                if( route(3) == "delete" ):
                    $id = route(4);
                    
                    if( countRow(["table"=>"files","where"=>["id"=>$id]]) ):
                        $delete = $conn->prepare("DELETE FROM files WHERE id=:id ");
                        $delete->execute(array("id"=>$id));
                    endif;
                    
                    header("Location:".site_url("admin/appearance/files"));
                    exit();
                endif;
                //1

  endif;

      if( route(5) ): header("Location:".site_url("admin/appearance/files")); 
 endif;
      
  endif;

  require admin_view('appearance');
