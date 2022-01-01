<!-- Messages are shown when a link with these attributes are clicked: href="#messages" rel="modal"  -->
  <br />
  <div class="pre_loader"><div class="listingPreloader"></div></div>
  <table class="form">
    <tbody>
        <tr>
          <td>
		  <?php 
		  if($remaining_total_crawl > 0 ):
		  	echo '<b>Scraper is online</b>';			
		  else:
		  	echo '<b>Scraper is offline</b>';
		  endif;
		  
		  echo '<br><br>Remaining Input to be processed : <b>'.$remaining_total_crawl.'<b>';
		  ?> </td>
        </tr>
    </tbody>
  </table>
  