<form method="post" action="/cms_ctrl" id="news_create_form" enctype="multipart/form-data">
    <div class="myDivHide" id="shownews">
        <div class="row">
            <div class="col-md-4 form-group">
                <label for="news_date">Date</label>
                <input type="date" name="news_date" id="news_date" class="form-control" required />
            </div>
            <div class="col-md-1 form-group">
            </div>
            <div class="col-md-7 form-group">
                <label for="news_tag">Tag</label>
                <input type="text" name="news_tag" id="news_tag" class="form-control" required />
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 form-group">
                <label for="news_title">Title</label>
                <input type="text" name="news_title" id="news_title" class="form-control" required />
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 form-group">
                <label for="news_link">URL</label>
                <input type="text" name="news_link" id="news_link" class="form-control" required />
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 form-group">
                <label for="news_text">Text</label>
                <textarea name="news_text" id="news_text" class="form-control" required></textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 form-group">
                <label for="news_reactText">Rich Text</label>
                <div name="news_reachText" id="news_reachText"></div>
            </div>
            <div class="col-md-12">
                <div class="file-area">
                    <label for="news_image">Images <span>Your image should be at least 300x300 pixels. Max file size should be <?php echo ini_get('upload_max_filesize'); ?>.</span></label>
                    <input type="file" name="news_image" id="news_image" accept="image/x-png,image/jpeg" required />
                    <div class="file-dummy">
                        <div class="success">Image is selected</div>
                        <div class="default">Upload only .png or .jpg files.</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="form-group mt-2">
            <input class="button" type="submit" name="news_create_btn" id="news_create_btn" value="Create" />
            &nbsp;&nbsp;&nbsp;
            <img src="/assets/lloading.gif" id="loader" style="display: none;" width="25%" />
        </div> -->
        <div class="row">
            <div class="col-md-12 form-group">
                <input class="button" style="margin-top:30px;" type="submit" name="news_create_btn" id="news_create_btn" value="Create Post" />
                <img src="/assets/loading.gif" alt="loading" id="loader" style="display: none;" padding-left:10px; width="25%" />
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        // ClassicEditor.create(document.querySelector("#news_reachText"), {
        //     toolbar: {
        //         items: [
        //             'heading',
        //             '|',
        //             'bold',
        //             'italic',
        //             'link',
        //             'bulletedList',
        //             'numberedList',
        //             '|',
        //             'codeBlock',
        //             'highlight',
        //             'blockQuote',
        //         ]
        //     },
        // }).then(editor => news_reachText = editor).catch(error => {
        //     console.error(error);
        // });
    });
</script>