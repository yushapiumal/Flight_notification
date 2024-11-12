<?php
    session_start();
    require_once('config/config.inc.php');
    require_once('CMS/cms.class.php');
    $cms = new cms();
    $tagList = $cms->getTagList('news'); 

    if (!isset($_SESSION['valid']) || $_SESSION['valid'] != true) {
        header('HTTP/1.1 401 Unauthorized');
        header('Location: /login');
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DigiCM - Digitable Content Management</title>
    <link rel="stylesheet" type="text/css" href="/assets/css/cms.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/css/v2Custom.css">
    <link href='https://fonts.googleapis.com/css?family=Lato:100,200,300,400,500,600,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.css" integrity="sha512-8D+M+7Y6jVsEa7RD6Kv/Z7EImSpNpQllgaEIQAtqHcI0H6F4iZknRj0Nx1DCdB+TwBaS+702BGWYC0Ze2hpExQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js" integrity="sha512-zlWWyZq71UMApAjih4WkaRpikgY9Bz1oXIW5G0fED4vk14JjGlQ1UmkGM392jEULP8jbNMiwLWdM8Z87Hu88Fw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.0/classic/ckeditor.js"></script>
    <style>
        /* ADDED BY AKESH 27-07-2023 */
        .error {
            color: #ff4a4a;
        }

        .myDivHide {
            display: none;
        }

        .nomarginRow {
            margin-right: 0px;
            margin-left: 0px;
        }
        .detailBox {
            border: 1px solid #656565;
            display: flex;
            flex-direction: row;
            font-family: sans-serif;
            padding-top: 0rem !important;
            margin-top: 1rem;
            min-height: 120px;
            max-height: 120px;
        }

        .detailBoxParagraph {
            color: black;
            /* display: flex; */
            /* flex-direction: column; */
            display: block;
            width: 18rem; 
            font-size: .7rem;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .detailBoxContent {
            padding: 5px;
        }

        .detailBoxContent span {
            font-size: .7rem;
            border-radius: 5px;
            padding: 5px;
            background-color: #656565;
        }

        .detailBoxImage {
            width: 15%;
            height: auto;
            /* height: 10rem; */
        }

        body {
            background-image: url(https://media.istockphoto.com/id/178414064/photo/blue-spot-lights.webp?b=1&s=170667a&w=0&k=20&c=5aDLTDPiXeocPrNZzafEblW18gvx9rrSVnKKtB9aHRM=);
            background-repeat: no-repeat;
            background-size: cover;
            color: black;
        }

        .collapsible {
            color: white;
            cursor: pointer;
            border: none;
            text-align: left;
            outline: none;
        }

        .collapsible:after {
            content: '\002B';
            color: white;
            font-weight: bold;
            float: right;
            margin-left: 5px;
        }

        .active:after {
            content: "\2212";
        }

        .content {
            padding: 0 18px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.2s ease-out;
        }

        .ck-editor__editable {
            min-height: 500px;
            max-height: 500px;
        }
    </style>
</head>

<body>
    <section class="container">
        <div class="row">
            <div class="col-md-5">
                <div class="row">
                    <div class="col-md-7 d-flex align-items-center justify-content-left">
                        <h4>Available Content</h4>
                    </div>
                    <div class="col-md-5">
                        <form>
                            <div class="form-group">
                                <!-- <label for="tagtype">Post Type</label> -->
                                <select id="tagType" class="form-control">
                                    <option selected value="promo">Promo</option>
                                    <!-- <option value="news">News</option> -->
                                    <option value="job">Job</option>
                                    <option value="blog">Blog</option>
                                    <optgroup label="Tags">
                                        <?php
                                        foreach ($tagList as  $row) {
                                            echo '<option value="' . $row . '">' . $row . '</option>';
                                        }
                                        ?>
                                    </optgroup>
                                    <optgroup label="Meta">
                                        <option value="robots.txt">Robots.txt</option>
                                        <option value="sitemap.xml">Sitemap.xml</option>
                                        <option value="imagesitemap.xml">ImageSitemap.xml</option>
                                    </optgroup>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="postrow">
                    <div id="list"></div>
                    <br>
                    <div id="paginationButtons"></div>
                </div>
            </div>
            <div class="col-md-7 border">
                <div class="row">
                    <div class="col-md-8 d-flex align-items-center justify-content-left">
                        <h4>Add New Content</h4>
                    </div>
                    <div class="col-md-4">
                        <form>
                            <div class="form-group">
                                <label for="type" class="form-inline">Type</label>
                                <select id="type" class="form-control">
                                    <option disabled selected value="">Select content type</option>
                                    <option value="promo">Promo</option>
                                    <!-- <option value="news">News</option> -->
                                    <option value="job">Job</option>
                                    <option value="blog">Blog</option>
                                    <option value="meta">Meta</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- PROMO FORM -->
                <?php require_once('cms_type-promo.php'); ?>
                <!-- NEWS FORM -->
                <?php //require_once('cms_type-news.php'); ?>
                <!-- JOB FORM -->
                <?php require_once('cms_type-job.php'); ?>
                <!-- Blog FORM -->
                <?php require_once('cms_type-blog.php'); ?>
                <!-- Meta FORM -->
                <?php require_once('cms_type-meta.php'); ?>
            </div>
        </div>
    </section>
    <section class="container">
        <div class="row">
            <div class="col-md-12"><pre><?php
                $writable = ['blog','promo','job','news'];
                foreach($writable as $dirType){
                    if(!is_writable(getcwd() . '/assets/'.$dirType.'/')){
                        echo $dirType.' not writable'."<br>";
                    }
                }
            ?></pre>
            </div>
        </div>
    </section>

    <a href="/" class="back-to-article" target="_blank">back to site</a>
    <script>
        $(document).ready(function() {
            var currentPage = 1;
            var itemsPerPage = 5;
            //var promo_reachText;
            //var news_reachText;
            //var job_reachText;
            var contentType = null;
            showList();

            function generatePaginationButtons(totalItems) {
                var totalPages = Math.ceil(totalItems / itemsPerPage);
                var paginationHTML = '';

                for (var i = 1; i <= totalPages; i++) { 
                    paginationHTML += '<button class="paginationButton" data-index="'+(i)+'">' + (i) + '</button>';
                }

                $('#paginationButtons').html(paginationHTML);
                $('.paginationButton').on('click', function(e){
                    goToPage($(this).data().index);
                });
            }

            $('#tagType').on('change', function(){
                showList();
            });

            function showList() {
                $('#list').empty();
                var formData = new FormData();
                var selected = $('#tagType').val();
                formData.append('tagType', selected);
                formData.append('page', currentPage);
                $.ajax({
                    type: 'post',
                    url: '/cms_ctrl',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,

                }).done(function(res) {
                    data = res.found;
                    if (res.found.length > 0) {
                        data.forEach(element => {
                            // let jobData = "<span class='collapsible'>View</span>" +
                            //     "<div class='content'>" +
                            //     "   <p>" + element.reachText + "</p>" +
                            //     "</div>";

                            let result = '<section class="detailBox">';
                            result    += '<img src="' + element.image + '" class="detailBoxImage" alt="'+element.imgAlt+'">';
                            result    += '<div class="detailBoxContent">';
                            result    += '<a href="' + element.link + '" target="_blank">' +element.title +'</a>';
                            result    += '<p class="detailBoxParagraph">' + element.text + '</p>';
                            result    += '<small>'+ ' ' + element.expDate + ' ' + '</small>';
                            if(element.removed == '1'){
                                // result += '<span id="activateBtn" onClick="deleteNActivateFunction('+element.id + ', \'activate\')">Activate</span>';
                                result += '<span id="activateBtn" class="activateBtn" data-element="'+element.id+'" data-type="activate">Activate</span>';

                            }
                            else {
                                // result += '<span id="deleteBtn" onClick="deleteNActivateFunction('+element.id + ', \'delete\')">Delete</span>';
                                result += '<span id="deleteBtn" class="deleteBtn" data-element="'+element.id+'" data-type="delete">Delete</span>';
                            }
                            result    += '</div>';
                            result    += '</section>';

                            $('#list').append(result);
                        });
                    } 
                    else {
                        $('#list').append('<p style="text-align: center;">No records found</p>');
                    }
                    $('.deleteBtn').on('click', function(e){
                        deleteNActivateFunction($(this).data().element, $(this).data().type);
                    });

                    $('.activateBtn').on('click', function(e){
                        deleteNActivateFunction($(this).data().element, $(this).data().type);
                    });

                    generatePaginationButtons(res.totalCount);
                });
            }

            function goToPage(page) {
                currentPage = page;
                showList();
            }

            // $(".collapsible").click(function() {
            //     $(this).toggleClass("active");
            //     var content = $(this).next();
            //     if (content.css("maxHeight")) {
            //         content.css("maxHeight", null);
            //     } else {
            //         content.css("maxHeight", content.prop("scrollHeight") + "px");
            //     }
            // });

            $('#type').on('change', function() {
                $("#" + contentType + "_create_form").trigger("reset");
                //promo_reachText.setData('');
                //news_reachText.setData('');
                //job_reachText.setData('');
                contentType = this.value;
                $("div.myDivHide").hide();
                $("#show" + contentType).show();
            });

            $("#meta_create_form").each(function(){
                $(this).validate({
                    submitHandler: function(form) {
                        var formData = new FormData();
                        let formKey = '#'+contentType;
                        formData.append('type', contentType);
                        formData.append('tag', $(formKey+'_category').val());
                        formData.append('text', $(formKey+'_text').val());
                        formData.append('doContentCreate', $(formKey+'_create_btn').val());
                        $.ajax({
                            type: 'post',
                            method: 'post',
                            url: $(form).attr('action'),
                            data: formData,
                            enctype: 'multipart/form-data',
                            cache: false,
                            dataType: 'json',
                            contentType: false,
                            processData: false
                        }).done(function(data) {
                            if (data.status) {
                                doShowToast(data.message, 'success');
                                $(formKey+'_create_form').trigger('reset'); 
                                showList();
                            } 
                            else {
                                doShowToast(data.message, 'error');
                            }
                            $(formKey+'_create_btn').removeAttr('disabled');
                        }).fail(function(data) {
                            doShowToast('Request failed', 'warning');
                            $(formKey+'_create_btn').removeAttr('disabled');
                        });
                    }
                });
                
            })

            $("#promo_create_form, #news_create_form, #job_create_form, #blog_create_form").each(function() {
                $(this).validate({
                    submitHandler: function(form) {
                        var formData = new FormData();
                        let formKey = '#'+contentType;
                        var image = $(formKey+'_image').prop('files')[0];
                        $(formKey+'_create_btn').prop('disabled', true);
                        formData.append('type', contentType);
                        formData.append('date', $(formKey+'_date').val());
                        formData.append('title', $(formKey+'_title').val());
                        formData.append('tag', $(formKey+'_tag').val());
                        formData.append('text', $(formKey+'_text').val());
                        formData.append('img_alt', $(formKey+'_alt').val());

                        if (contentType == 'promo') {
                        //    formData.append('richText', promo_reachText.getData());
                        }
                        // if (contentType == 'news') {
                        //     formData.append('richText', news_reachText.getData());
                        // }
                        if (contentType == 'job') {
                          //  formData.append('richText', job_reachText.getData());
                        }

                        if (contentType == 'blog') {
                            formData.append('slug', $(formKey + '_link').val());
                            formData.append('publised_on', $(formKey + '_date').val());
                            formData.append('richText', blog_richText.getData());
                        }

                        formData.append('link', $(formKey+'_link').val());
                        formData.append('expDate', $(formKey+'_expDate').val());
                        formData.append('image', image);
                        formData.append('doContentCreate', $(formKey+'_create_btn').val());

                        $.ajax({
                            type: 'post',
                            method: 'post',
                            url: $(form).attr('action'),
                            data: formData,
                            enctype: 'multipart/form-data',
                            cache: false,
                            dataType: 'json',
                            contentType: false,
                            processData: false
                        }).done(function(data) {
                            if (data.status) {
                                doShowToast(data.message, 'success');
                                $(formKey+'_create_form').trigger('reset');
                                //promo_reachText.setData('');
                                //news_reachText.setData('');
                                //job_reachText.setData('');
                                blog_richText.setData('');
                                showList();
                            } 
                            else {
                                doShowToast(data.message, 'error');
                            }
                            $(formKey+'_create_btn').removeAttr('disabled');
                        }).fail(function(data) {
                            doShowToast('Request failed', 'warning');
                            $(formKey+'_create_btn').removeAttr('disabled');
                        });
                    }
                });
            });

            function doShowToast(text, icon) {
                // icon = 'warning', 'success', 'info', 'error';
                $.toast({
                    heading: 'Alert',
                    text: text,
                    position: 'top-right',
                    icon: icon
                });
            }

            

            function deleteNActivateFunction(val, type) {
                var formData = new FormData();
                formData.append('id', val);
                if (type == 'delete') {
                    formData.append('deleteBtn', $('#deleteBtn').val());
                } else {
                    formData.append('activateBtn', $('#activateBtn').val());
                }

                $.ajax({
                    type: "post",
                    method: 'post',
                    url: '/cms_ctrl',
                    data: formData,
                    cache: false,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                }).done(function(data) {

                    if (data.status) {
                        doShowToast(data.message, 'success');
                    } else {
                        doShowToast(data.message, 'error');
                    }
                    showList();
                }).fail(function(data) {
                    doShowToast('Request failed', 'warning');
                    $('#newsBtn').removeAttr('disabled');
                });
            }
        });
    </script>

</body>

</html>