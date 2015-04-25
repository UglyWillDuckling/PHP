

		<?php echo clean($video['like']);
			  if($love_hate) 
			  { 
			?>
			  	<a href='<?php echo clean($loveLink) . "1"; ?>'> likes</a>
				
				<?php echo clean($video['dislike']); ?> 
				<a href='<?php echo clean($loveLink) . "2"; ?>'> dislikes</a> 
			<?php 
				}
				else 
				{
			?>		
				likes
				<?php echo clean($video['dislike']); ?>
				dislikes
			<?php
				}
			?>	

