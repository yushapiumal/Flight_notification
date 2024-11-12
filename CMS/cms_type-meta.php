<form method="post" action="/cms_ctrl" id="meta_create_form">
    <div class="myDivHide" id="showmeta">
        <div class="row">
            <div class="col-md-12 form-group">
                <label for="meta_category">Category</label>
                <select name="meta_category" id="meta_category" class="form-control" required>
                    <option disabled selected value="">Select category type</option>
                    <option value="robots.txt">Robots.txt</option>
                    <option value="sitemap.xml">Sitemap.xml</option>
                    <option value="imagesitemap.xml">imageSitemap.xml</option>
                </select>
            </div>
        </div>
         
        <div class="row">
            <div class="col-md-12 form-group">
                <label for="meta_text">Text/Lead&nbsp;
                    <small>Summarized version. (Max: 300 characters)</small>
                </label>
                <textarea name="meta_text" id="meta_text" rows="4" class="form-control" required></textarea>
            </div>
        </div>
         
        <div class="row">
            <div class="col-md-12 form-group">
                <input class="button" style="margin-top:30px;" type="submit" name="meta_create_btn" id="meta_create_btn" value="Create Meta" />
                <img src="/assets/loading.gif" id="loader" style="display: none;" padding-left:10px; width="25%" />
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
     
    });
</script>