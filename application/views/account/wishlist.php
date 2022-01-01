<div class="main-container col2-left-layout">
            <div class="main">
                				<div class="container">
					<div class="row">					
						<?php $this->load->view('account/leftbar_box');?>						
						<div class="col-lg-9 col-md-9 col-main">
														<div id="map-popup" class="map-popup" style="display:none;">
    <a href="#" class="map-popup-close" id="map-popup-close">x</a>
    <div class="map-popup-arrow"></div>
    <div class="map-popup-heading"><h2 id="map-popup-heading"></h2></div>
    <div class="map-popup-content" id="map-popup-content">
        <div class="map-popup-checkout">
            <form action="" method="POST" id="product_addtocart_form_from_popup">
                <input type="hidden" name="product" class="product_id" value="" id="map-popup-product-id">
                <div class="additional-addtocart-box">
                                    </div>
                <button type="button" title="Add to Cart" class="button btn-cart" id="map-popup-button"><span><span>Add to Cart</span></span></button>
            </form>
        </div>
        <div class="map-popup-msrp" id="map-popup-msrp-box"><strong>Price:</strong> <span style="text-decoration:line-through;" id="map-popup-msrp"></span></div>
        <div class="map-popup-price" id="map-popup-price-box"><strong>Actual Price:</strong> <span id="map-popup-price"></span></div>
        <script type="text/javascript">
        //<![CDATA[
            document.observe("dom:loaded", Catalog.Map.bindProductForm);
        //]]>
        </script>
    </div>
    <div class="map-popup-text" id="map-popup-text">Our price is lower than the manufacturer's "minimum advertised price."  As a result, we cannot show you the price in catalog or the product page. <br><br> You have no obligation to purchase the product once you know the price. You can simply remove the item from your cart.</div>
    <div class="map-popup-text" id="map-popup-text-what-this">Our price is lower than the manufacturer's "minimum advertised price."  As a result, we cannot show you the price in catalog or the product page. <br><br> You have no obligation to purchase the product once you know the price. You can simply remove the item from your cart.</div>
</div>
<div class="my-account"><div class="my-wishlist">
        <div class="page-title title-buttons">
                        <h1>My Wishlist</h1>
        </div>
        
        <form id="wishlist-view-form" action="http://demo.flytheme.net/themes/sm_stationery/wishlist/index/update/wishlist_id/86/" method="post">
                        <div class="overflow-table">
                                        <input name="form_key" type="hidden" value="TLSrYmq2UKrCzVXm">
                                                <table class="data-table" id="wishlist-table">
    <thead>
        <tr class="first last">
                            <th></th>
                            <th>Product Details and Comment</th>
                            <th>Add to Cart</th>
                            <th></th>
                    </tr>
    </thead>
    <tbody>
                                    <tr id="item_211" class="first last odd">
                                            <td><a class="product-image" href="http://demo.flytheme.net/themes/sm_stationery/tuikan-mipam.html" title="Tuikan mipam">
    <img src="http://demo.flytheme.net/themes/sm_stationery/media/catalog/product/cache/4/small_image/113x113/9df78eab33525d08d6e5fb8d27136e95/9/_/9_3.png" alt="Tuikan mipam">
</a>
</td>
                                            <td><h3 class="product-name"><a href="http://demo.flytheme.net/themes/sm_stationery/tuikan-mipam.html" title="Tuikan mipam">Tuikan mipam</a></h3>
<div class="description std"><div class="inner">Turducken chuck hamburger ullamco, doner pastrami pork chop irure. Consectetur sint leberkas boudin, do enim exercitation shank tongue. Prosciutto ex ham hock ground round hamburger. Sed shoulder meatloaf, incididunt laboris do picanha filet mignon tail. Boudin capicola tail deserunt turkey cillum. Alcatra elit swine pariatur nulla</div></div>
<textarea name="description[211]" rows="3" cols="5" onfocus="focusComment(this)" onblur="focusComment(this)" title="Comment">Please, enter your comments...</textarea>
</td>
                                            <td><div class="cart-cell">


                        
    <div class="price-box">
                                                                <span class="regular-price">
                                            						
						<span class="price">$82.00</span>                                    </span>
                        
        </div>

<div class="add-to-cart-alt">
    <input type="text" class="input-text qty validate-not-negative-number" name="qty[211]" value="1">
    <button type="button" title="Add to Cart" onclick="addWItemToCart(211);" class="button btn-cart"><span><span>Add to Cart</span></span></button>
</div>
    
    <p><a class="link-edit" href="http://demo.flytheme.net/themes/sm_stationery/wishlist/index/configure/id/211/">Edit</a></p>
</div>
</td>
                                            <td class="last"><a href="http://demo.flytheme.net/themes/sm_stationery/wishlist/index/remove/item/211/" title="Remove Item" class="btn-remove btn-remove2">Remove item</a>
</td>
                                    </tr>
                        </tbody>
</table>
                <script type="text/javascript">
//<![CDATA[
    decorateTable('wishlist-table');

        
        function focusComment(obj) {
            if( obj.value == 'Please, enter your comments...' ) {
                obj.value = '';
            } else if( obj.value == '' ) {
                obj.value = 'Please, enter your comments...';
            }
        }
            
            function addWItemToCart(itemId) {
                var url = 'http://demo.flytheme.net/themes/sm_stationery/wishlist/index/cart/item/%item%/uenc/aHR0cDovL2RlbW8uZmx5dGhlbWUubmV0L3RoZW1lcy9zbV9zdGF0aW9uZXJ5L3dpc2hsaXN0Lz9fX19zdG9yZT1hcmdlbnRpbmE,/form_key/TLSrYmq2UKrCzVXm/';
                url = url.gsub('%item%', itemId);
                var form = $('wishlist-view-form');
                if (form) {
                    var input = form['qty[' + itemId + ']'];
                    if (input) {
                        var separator = (url.indexOf('?') >= 0) ? '&' : '?';
                        url += separator + input.name + '=' + encodeURIComponent(input.value);
                    }
                }
                setLocation(url);
            }
            
        function confirmRemoveWishlistItem() {
            return confirm('Are you sure you want to remove this product from your wishlist?');
        }
        //]]>
</script>
                        <script type="text/javascript">decorateTable('wishlist-table')</script>
                			</div>
                <div class="buttons-set buttons-set2">
                    
    <button type="submit" name="save_and_share" title="Share Wishlist" class="button btn-share"><span><span>Share Wishlist</span></span></button>

    <button type="button" title="Add All to Cart" onclick="addAllWItemsToCart()" class="button btn-add"><span><span>Add All to Cart</span></span></button>

    <button type="submit" name="do" title="Update Wishlist" class="button btn-update"><span><span>Update Wishlist</span></span></button>
                </div>
            
        </form>

        <form id="wishlist-allcart-form" action="http://demo.flytheme.net/themes/sm_stationery/wishlist/index/allcart/" method="post">
            <input name="form_key" type="hidden" value="TLSrYmq2UKrCzVXm">
            <div class="no-display">
                <input type="hidden" name="wishlist_id" id="wishlist_id" value="86">
                <input type="hidden" name="qty" id="qty" value="">
            </div>
        </form>

        <script type="text/javascript">
        //<![CDATA[
            var wishlistForm = new Validation($('wishlist-view-form'));
            var wishlistAllCartForm = new Validation($('wishlist-allcart-form'));

            function calculateQty() {
                var itemQtys = new Array();
                $$('#wishlist-view-form .qty').each(
                    function (input, index) {
                        var idxStr = input.name;
                        var idx = idxStr.replace( /[^\d.]/g, '' );
                        itemQtys[idx] = input.value;
                    }
                );

                $$('#qty')[0].value = JSON.stringify(itemQtys);
            }

            function addAllWItemsToCart() {
                calculateQty();
                wishlistAllCartForm.form.submit();
            }
        //]]>
        </script>
    </div>
        <div class="buttons-set">
        <p class="back-link"><a href="http://demo.flytheme.net/themes/sm_stationery/customer/account/"><small>« </small>Back</a></p>
    </div></div>						</div>						
					</div>
				</div>
				            </div>
        </div>