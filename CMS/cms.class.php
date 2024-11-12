<?php

class cms
{
    private $db_conn;

    public function __construct()
    {
        $this->DbConnect();
    }

    private function DbConnect()
    {
        try {
            $servername = __CMS_SERVER;
            $username = __CMS_DB_USER;
            $password = __CMS_DB_PASS;
            $dbname = __CMS_DB_NAME;

            $this->db_conn = new mysqli($servername, $username, $password, $dbname);
            if ($this->db_conn->connect_error) {
                die('Connection failed: ' . $this->db_conn->connect_error);
            }
        }
        catch(Exception $e){
            echo ($e->getMessage().' '.$e->getFile(). ' at '.$e->getLine());
            exit;
        }        
    }

    private function doGetSimple($contentData)
    {
        $details = unserialize($contentData['details']);

        if($contentData['type'] == 'meta'){
            return [
                'tag' => $contentData['tag'],
                'title' => $contentData['tag'],
                'text' => $details['text'],
                'reachText' => $contentData['type'],
                'id' => $contentData['id'],
                'removed' => $contentData['removed'],
                'link' => "#",
                'image' => 'https://static.thenounproject.com/png/4199163-200.png',
                'created_at' => $contentData['created_at'],
                'expDate' =>  $contentData['date']
            ];
        } else{
            $assetPath = '/assets/' . $contentData['type'] . '/';
            return [
                'tag' => $contentData['tag'],
                'title' => $details['title'],
                'date' => $contentData['date'],
                'expDate' => $contentData['expire_date'],
                'image' =>   $assetPath . $details['image'],
                'imgAlt' => isset($details['img_alt'])? $details['img_alt']:"",
                'text' => $details['text'],
                'reachText' => $details['richText'],
                'id' => $contentData['id'],
                'link' => $details['link'],
                'removed' => $contentData['removed'],
                'created_at' => $contentData['created_at'],
            ];
        }
    }

    public function getMeta($type){
        switch($type){
            case 'sitemap.xml':
                $sql = "SELECT * FROM content WHERE tag ='sitemap.xml' AND type='meta' AND removed = 0 order by id asc limit 1;";
                break;

            case 'robots.txt':
                $sql = "SELECT * FROM content WHERE tag ='robots.txt' AND type='meta' AND removed = 0 order by id asc limit 1;";
                break;

            case 'imagesitemap.xml':
                $sql = "SELECT * FROM content WHERE tag ='imagesitemap.xml' AND type='meta' AND removed = 0 order by id asc limit 1;";
                break;
        }

        $result = $this->db_conn->query($sql);
        if (!empty($result)) {
            foreach ($result as $metaDetails) {
                return $metaDetails;
            }
        }

        return false;
    }

    public function getBlogs($slug = false)
    {
        $sql = "SELECT * FROM content WHERE type ='blog' AND removed = 0";
        if (is_string($slug)) {
            $sql = "SELECT * FROM content WHERE type ='blog' AND slug='" . $slug . "' AND removed = 0";
        }
        $sql= $sql.' order by id desc;';
        $result = $this->db_conn->query($sql);
        $blogsArray = [];
        if (!empty($result)) {
            foreach ($result as $contentData) {
                array_push($blogsArray, $this->doGetSimple($contentData));
            }
        }
        return $blogsArray;
    }

    public function getNews($expireTs = false)
    {
        $sql = "SELECT * FROM content WHERE type ='news' AND removed = 0";
        $sql= $sql.' order by id desc;';
        $result = $this->db_conn->query($sql);
        $newsArray = [];
        if (!empty($result)) {
            foreach ($result as $contentData) {
                array_push($newsArray, $this->doGetSimple($contentData));
            }
        }
        return $newsArray;
    }

    public function addContent($formData, $fileData)
    {
        try {
          
            $type = $formData['type'];

            if($type == 'meta'){
                $tag = $formData['tag'];
                $details = serialize([
                    'text' => $formData['text'],
                ]);

                $sql = vsprintf(
                    "INSERT INTO content ( `details` , `tag`, `type`, `date`) 
                        VALUES ('%s','%s','%s','%s')",
                    [
                        $this->db_conn->real_escape_string($details),
                        $this->db_conn->real_escape_string($tag),
                        $this->db_conn->real_escape_string($type),
                        $this->db_conn->real_escape_string(date("Y-m-d")),
                    ]
                );

                if(mysqli_query($this->db_conn, $sql)){
                    echo json_encode([
                        'status' => true,
                        'message' => 'Content saved successfully'
                    ]);
                }else{
                    echo json_encode([
                        'status' => false,
                        'message' => 'Content not saved'
                    ]);
                }
                exit;
            }

            $slug = isset($formData['slug']) ? $formData['slug'] : '';
            $date = ($formData['date'] == 'undefined') ? "" : $formData['date'];
            $expDate = ($formData['expDate'] == 'undefined') ? "" : $formData['expDate'];
            $tag = $formData['tag'];
            $image = $fileData['image'];
            $imageAlt = $formData['img_alt'];
            $fileName = time() . '_' . getRandomStringRand() . '.' . explode("/", $image['type'])[1];
            $details = serialize([
                'title' => $formData['title'],
                'text' => $formData['text'],
                'richText' => isset($formData['richText']) ? $formData['richText'] : '',
                'link' => $formData['link'],
                'slug' => $slug,
                'image' => $fileName,
                'img_alt' => $imageAlt
            ]);

            $sql = vsprintf(
                "INSERT INTO content ( `details` , `expire_date`,  `slug`, `tag`, `type`, `date`) 
                    VALUES ('%s','%s','%s','%s','%s','%s')",
                [
                    $this->db_conn->real_escape_string($details),
                    $this->db_conn->real_escape_string($expDate),
                    $this->db_conn->real_escape_string($slug),
                    $this->db_conn->real_escape_string($tag),
                    $this->db_conn->real_escape_string($type),
                    $this->db_conn->real_escape_string($date),
                ]
            );


            $directoryName = getcwd() . '/assets/' . $type . '/';

            if (is_writable($directoryName)) {
                if (move_uploaded_file($image['tmp_name'], $directoryName . $fileName)) {
                    mysqli_query($this->db_conn, $sql);
                    echo json_encode([
                        'status' => true,
                        'message' => 'Content saved successfully'
                    ]);
                    exit;
                } else {
                    echo json_encode([
                        'status' => false,
                        'message' => 'Content uploading failed'
                    ]);
                    exit;
                }
            }
            echo json_encode([
                'status' => false,
                'message' => 'Image uploading failed'
            ]);
            exit;
        } catch (\Throwable $th) {
            echo json_encode([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function deleteContentOrNewsById($id)
    {
        $sql = "UPDATE content SET removed='1' WHERE id=$id";
        if ($this->db_conn->query($sql) === TRUE) {
            echo json_encode([
                'status' => true,
                'message' => "Record deleted successfully"
            ]);
            exit;
        } else {
            echo json_encode([
                'status' => false,
                'message' => "Error updating record: " . $this->db_conn->error
            ]);
            exit;
        }
    }
    public function activateContentOrNewsById($id)
    {
        $sql = "UPDATE content SET removed='0' WHERE id=$id";
        if ($this->db_conn->query($sql) === TRUE) {
            echo json_encode([
                'status' => true,
                'message' => "Record activated successfully"
            ]);
            exit;
        } else {
            echo json_encode([
                'status' => false,
                'message' => "Error updating record: " . $this->db_conn->error
            ]);
            exit;
        }
    }


    public function getTagList($type)
    {
        $sql = "SELECT tag FROM content WHERE type !='meta' AND removed = 0";
        $result = $this->db_conn->query($sql);
        $arr = [];
        if (!empty($result)) {
            foreach ($result as $value) {
                if (!empty($value['tag']) && !is_null($value['tag'])) {
                    array_push($arr, $value['tag']);
                }
            }
        }
        $new = array_unique($arr);
        return $new;
    }

    public function getPromo($expireTs = false, $limit = false)
    {
        if ($expireTs) {
            $currentDate = date('Y-m-d');
            $sql = "SELECT * FROM content WHERE type ='promo' AND removed = 0 AND expire_date >= '$currentDate'";
        } else {
            $sql = "SELECT * FROM content  WHERE type ='promo' AND removed = 0";
        }

        $sql .= ' order by id desc ';

        if($limit){
            $sql .= ' limit '.$limit;
        }
        
        $result = $this->db_conn->query($sql);
        $myArray = [];
        if (!empty($result)) {
            $assetPath = '/assets/promo/';

            foreach ($result as $contentData) {
                array_push($myArray, $this->doGetSimple($contentData));
            }
        }
        return $myArray;
    }

    public function getJobs($expireTs = false)
    {
        if ($expireTs) {
            $currentDate = date('Y-m-d');
            // $sql = "SELECT * FROM content WHERE type ='job' AND removed = 0 AND expire_date >= '$currentDate'";
            $sql = "SELECT * FROM content  WHERE type ='job' AND removed = 0";
        } else {
            $sql = "SELECT * FROM content  WHERE type ='job' AND removed = 0";
        }
        $result = $this->db_conn->query($sql);
        $myArray = [];
        if (!empty($result)) {
            foreach ($result as $contentData) {
                array_push($myArray, $this->doGetSimple($contentData));
            }
        }
        return $myArray;
    }

    public function getTag($tag, $page=1)
    {
        if($page === true){
            $sql = "SELECT count(*) as `totalRows` FROM content  WHERE `type` = '{$tag}'";
            $result = $this->db_conn->query($sql);
            $result=mysqli_fetch_assoc($result);

            return $result['totalRows'];
        }

        $itemsPerPage = 5;
        $offset = ($page * $itemsPerPage) - $itemsPerPage;
        //$offset = ($page - 1) * $itemsPerPage;

        //$assetPath = '/assets/promo/';
        $found = [];

        if (in_array($tag, ['news','promo','job','blog'])) {
            //$assetPath = '/assets/' . $tag . '/';
            $sql = "SELECT * FROM content  WHERE `type` = '{$tag}'";
        } else {
            //$assetPath = '/assets/promo/';
            $sql = "SELECT * FROM content  WHERE `tag` = '{$tag}'";
        }

        $sql .= " ORDER BY `id` DESC LIMIT $itemsPerPage OFFSET $offset";

        $result = $this->db_conn->query($sql);
        if (!empty($result)) {
            foreach ($result as $contentData) {
                array_push($found, $this->doGetSimple($contentData));
            }
        }

        return $found;
    }

    public function getGallery()
    {
        $sql = "SELECT * FROM content WHERE tag = 'gallery' AND removed = '0'";
        $result = $this->db_conn->query($sql);
        return $result;
    }

    public function loginUser($uname, $pass)
    {
        if (($uname == 'eme@mackairlkcmb' && $pass == 'UlhKURMKA#2024') || 
            ($uname == 'editor@mackairlkcmb' && $pass == 'TlhpolMKA@2023')) {
            $_SESSION['valid'] = true;
            echo json_encode(['status' => true]);
        } else {
            echo json_encode(['status' => false]);
        }
    }

    public function logout()
    {
        $_SESSION['valid'] = false;
    }

    public function getCountryCode(){
        return [
            '44' => 'UK (+44)',
            '1' => 'USA (+1)',
            '213' => 'Algeria (+213)',
            '376' => 'Andorra (+376)',
            '244' => 'Angola (+244)',
            '1264' => 'Anguilla (+1264)',
            '1268' => 'Antigua & Barbuda (+1268)',
            '54' => 'Argentina (+54)',
            '374' => 'Armenia (+374)',
            '297' => 'Aruba (+297)',
            '61' => 'Australia (+61)',
            '43' => 'Austria (+43)',
            '994' => 'Azerbaijan (+994)',
            '1242' => 'Bahamas (+1242)',
            '973' => 'Bahrain (+973)',
            '880' => 'Bangladesh (+880)',
            '1246' => 'Barbados (+1246)',
            '375' => 'Belarus (+375)',
            '32' => 'Belgium (+32)',
            '501' => 'Belize (+501)',
            '229' => 'Benin (+229)',
            '1441' => 'Bermuda (+1441)',
            '975' => 'Bhutan (+975)',
            '591' => 'Bolivia (+591)',
            '387' => 'Bosnia Herzegovina (+387)',
            '267' => 'Botswana (+267)',
            '55' => 'Brazil (+55)',
            '673' => 'Brunei (+673)',
            '359' => 'Bulgaria (+359)',
            '226' => 'Burkina Faso (+226)',
            '257' => 'Burundi (+257)',
            '855' => 'Cambodia (+855)',
            '237' => 'Cameroon (+237)',
            '1' => 'Canada (+1)',
            '238' => 'Cape Verde Islands (+238)',
            '1345' => 'Cayman Islands (+1345)',
            '236' => 'Central African Republic (+236)',
            '56' => 'Chile (+56)',
            '86' => 'China (+86)',
            '57' => 'Colombia (+57)',
            '269' => 'Comoros (+269)',
            '242' => 'Congo (+242)',
            '682' => 'Cook Islands (+682)',
            '506' => 'Costa Rica (+506)',
            '385' => 'Croatia (+385)',
            '53' => 'Cuba (+53)',
            '90392' => 'Cyprus North (+90392)',
            '357' => 'Cyprus South (+357)',
            '42' => 'Czech Republic (+42)',
            '45' => 'Denmark (+45)',
            '253' => 'Djibouti (+253)',
            '1809' => 'Dominica (+1809)',
            '1809' => 'Dominican Republic (+1809)',
            '593' => 'Ecuador (+593)',
            '20' => 'Egypt (+20)',
            '503' => 'El Salvador (+503)',
            '240' => 'Equatorial Guinea (+240)',
            '291' => 'Eritrea (+291)',
            '372' => 'Estonia (+372)',
            '251' => 'Ethiopia (+251)',
            '500' => 'Falkland Islands (+500)',
            '298' => 'Faroe Islands (+298)',
            '679' => 'Fiji (+679)',
            '358' => 'Finland (+358)',
            '33' => 'France (+33)',
            '594' => 'French Guiana (+594)',
            '689' => 'French Polynesia (+689)',
            '241' => 'Gabon (+241)',
            '220' => 'Gambia (+220)',
            '7880' => 'Georgia (+7880)',
            '49' => 'Germany (+49)',
            '233' => 'Ghana (+233)',
            '350' => 'Gibraltar (+350)',
            '30' => 'Greece (+30)',
            '299' => 'Greenland (+299)',
            '1473' => 'Grenada (+1473)',
            '590' => 'Guadeloupe (+590)',
            '671' => 'Guam (+671)',
            '502' => 'Guatemala (+502)',
            '224' => 'Guinea (+224)',
            '245' => 'Guinea - Bissau (+245)',
            '592' => 'Guyana (+592)',
            '509' => 'Haiti (+509)',
            '504' => 'Honduras (+504)',
            '852' => 'Hong Kong (+852)',
            '36' => 'Hungary (+36)',
            '354' => 'Iceland (+354)',
            '91' => 'India (+91)',
            '62' => 'Indonesia (+62)',
            '98' => 'Iran (+98)',
            '964' => 'Iraq (+964)',
            '353' => 'Ireland (+353)',
            '972' => 'Israel (+972)',
            '39' => 'Italy (+39)',
            '1876' => 'Jamaica (+1876)',
            '81' => 'Japan (+81)',
            '962' => 'Jordan (+962)',
            '7' => 'Kazakhstan (+7)',
            '254' => 'Kenya (+254)',
            '686' => 'Kiribati (+686)',
            '850' => 'Korea North (+850)',
            '82' => 'Korea South (+82)',
            '965' => 'Kuwait (+965)',
            '996' => 'Kyrgyzstan (+996)',
            '856' => 'Laos (+856)',
            '371' => 'Latvia (+371)',
            '961' => 'Lebanon (+961)',
            '266' => 'Lesotho (+266)',
            '231' => 'Liberia (+231)',
            '218' => 'Libya (+218)',
            '417' => 'Liechtenstein (+417)',
            '370' => 'Lithuania (+370)',
            '352' => 'Luxembourg (+352)',
            '853' => 'Macao (+853)',
            '389' => 'Macedonia (+389)',
            '261' => 'Madagascar (+261)',
            '265' => 'Malawi (+265)',
            '60' => 'Malaysia (+60)',
            '960' => 'Maldives (+960)',
            '223' => 'Mali (+223)',
            '356' => 'Malta (+356)',
            '692' => 'Marshall Islands (+692)',
            '596' => 'Martinique (+596)',
            '222' => 'Mauritania (+222)',
            '269' => 'Mayotte (+269)',
            '52' => 'Mexico (+52)',
            '691' => 'Micronesia (+691)',
            '373' => 'Moldova (+373)',
            '377' => 'Monaco (+377)',
            '976' => 'Mongolia (+976)',
            '1664' => 'Montserrat (+1664)',
            '212' => 'Morocco (+212)',
            '258' => 'Mozambique (+258)',
            '95' => 'Myanmar (+95)',
            '264' => 'Namibia (+264)',
            '674' => 'Nauru (+674)',
            '977' => 'Nepal (+977)',
            '31' => 'Netherlands (+31)',
            '687' => 'New Caledonia (+687)',
            '64' => 'New Zealand (+64)',
            '505' => 'Nicaragua (+505)',
            '227' => 'Niger (+227)',
            '234' => 'Nigeria (+234)',
            '683' => 'Niue (+683)',
            '672' => 'Norfolk Islands (+672)',
            '670' => 'Northern Marianas (+670)',
            '47' => 'Norway (+47)',
            '968' => 'Oman (+968)',
            '680' => 'Palau (+680)',
            '507' => 'Panama (+507)',
            '675' => 'Papua New Guinea (+675)',
            '595' => 'Paraguay (+595)',
            '51' => 'Peru (+51)',
            '63' => 'Philippines (+63)',
            '48' => 'Poland (+48)',
            '351' => 'Portugal (+351)',
            '1787' => 'Puerto Rico (+1787)',
            '974' => 'Qatar (+974)',
            '262' => 'Reunion (+262)',
            '40' => 'Romania (+40)',
            '7' => 'Russia (+7)',
            '250' => 'Rwanda (+250)',
            '378' => 'San Marino (+378)',
            '239' => 'Sao Tome & Principe (+239)',
            '966' => 'Saudi Arabia (+966)',
            '221' => 'Senegal (+221)',
            '381' => 'Serbia (+381)',
            '248' => 'Seychelles (+248)',
            '232' => 'Sierra Leone (+232)',
            '65' => 'Singapore (+65)',
            '421' => 'Slovak Republic (+421)',
            '386' => 'Slovenia (+386)',
            '677' => 'Solomon Islands (+677)',
            '252' => 'Somalia (+252)',
            '27' => 'South Africa (+27)',
            '34' => 'Spain (+34)',
            '94' => 'Sri Lanka (+94)',
            '290' => 'St. Helena (+290)',
            '1869' => 'St. Kitts (+1869)',
            '1758' => 'St. Lucia (+1758)',
            '249' => 'Sudan (+249)',
            '597' => 'Suriname (+597)',
            '268' => 'Swaziland (+268)',
            '46' => 'Sweden (+46)',
            '41' => 'Switzerland (+41)',
            '963' => 'Syria (+963)',
            '886' => 'Taiwan (+886)',
            '7' => 'Tajikstan (+7)',
            '66' => 'Thailand (+66)',
            '228' => 'Togo (+228)',
            '676' => 'Tonga (+676)',
            '1868' => 'Trinidad & Tobago (+1868)',
            '216' => 'Tunisia (+216)',
            '90' => 'Turkey (+90)',
            '7' => 'Turkmenistan (+7)',
            '993' => 'Turkmenistan (+993)',
            '1649' => 'Turks & Caicos Islands (+1649)',
            '688' => 'Tuvalu (+688)',
            '256' => 'Uganda (+256)',
            '380' => 'Ukraine (+380)',
            '971' => 'United Arab Emirates (+971)',
            '598' => 'Uruguay (+598)',
            '7' => 'Uzbekistan (+7)',
            '678' => 'Vanuatu (+678)',
            '379' => 'Vatican City (+379)',
            '58' => 'Venezuela (+58)',
            '84' => 'Vietnam (+84)',
            '84' => 'Virgin Islands - British (+1284)',
            '84' => 'Virgin Islands - US (+1340)',
            '681' => 'Wallis & Futuna (+681)',
            '969' => 'Yemen (North)(+969)',
            '967' => 'Yemen (South)(+967)',
            '260' => 'Zambia (+260)',
            '263' => 'Zimbabwe (+263)',
        ]; 
    }
}
