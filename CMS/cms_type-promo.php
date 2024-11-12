<form method="post" action="/cms_ctrl" id="promo_create_form" enctype="multipart/form-data">
    <div class="myDivHide" id="showpromo">
        <div class="row">
            <div class="col-md-4 form-group">
                <label for="promo_expDate">Expiry date</label>
                <input type="date" name="promo_expDate" id="promo_expDate" class="form-control" required />
            </div>
            <div class="col-md-1">
            </div>
            <div class="col-md-7 form-group">
                <label for="blog_tag">Tags <small>If multiple? use commas to separate</small></label>
                <input type="text" name="promo_tag" id="promo_tag" class="form-control" required />
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 form-group">
                <label for="blog_title">Promo title <small>Title for the promo</small></label>
                <input type="text" name="promo_title" id="promo_title" class="form-control" required />
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 form-group">
                <label for="promo_link">URL <small>FB URL of the promo post</small></label>
                <input type="url" name="promo_link" id="promo_link" class="form-control" required />
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 form-group">
                <label for="promo_text">Text/Lead&nbsp;
                    <small>Summarized version. (Max: 300 characters)</small>
                </label>
                <textarea name="promo_text" id="promo_text" rows="4" class="form-control" required></textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 file-area">
                <label for="images">
                    Promo Image <small>Image should be at least 300x300 pixels. Max file size <?php echo ini_get('upload_max_filesize'); ?>.</small>.</span>
                </label>
                <input type="file" name="promo_image" id="promo_image" accept="image/x-png,image/jpeg" required />
                <div class="file-dummy">
                    <div class="success">Image is selected</div>
                    <div class="default">Upload only .png or .jpg files.</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 form-group">
                <label for="promo_alt">Image ALT</label>
                <input type="text" name="promo_alt" id="promo_alt" class="form-control" required />
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 form-group">
                <input class="button" style="margin-top:30px;" type="submit" name="promo_create_btn" id="promo_create_btn" value="Create Post" />
                <img src="/assets/loading.gif" alt="loading" id="loader" style="display: none;" padding-left:10px; width="25%" />
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        // ClassicEditor.create(document.querySelector("#promo_reachText"), {
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
        // }).then(editor => promo_reachText = editor).catch(error => {
        //     console.error(error);
        // });
    });
</script>