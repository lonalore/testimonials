<?php
/**
 * Testimonials plugin for e107 v2.
 *
 * @file
 * Templates for plugins displays.
 */

$TESTIMONIALS_TEMPLATE['menu_header'] = '
<div class="container">
	<div class="row">
		<div class="col-md-12" data-wow-delay="0.2s">

			<div class="carousel slide" data-ride="carousel" id="quote-carousel">
				<!-- Bottom Carousel Indicators -->
				{TESTIMONIAL_INDICATORS}

				<!-- Carousel Slides / Quotes -->
				<div class="carousel-inner text-center">';

$TESTIMONIALS_TEMPLATE['menu_body'] = '
					<div class="item">
						<blockquote>
							<div class="row">
								<div class="col-sm-8 col-sm-offset-2">
									<p>{TESTIMONIAL_MESSAGE}</p>
									<small>{TESTIMONIAL_AUTHOR}</small>
								</div>
							</div>
						</blockquote>
					</div>';

$TESTIMONIALS_TEMPLATE['menu_footer'] = '
				</div>

				<!-- Carousel Buttons Next/Prev -->
				<a data-slide="prev" href="#quote-carousel" class="left carousel-control"><i class="fa fa-chevron-left"></i></a>
				<a data-slide="next" href="#quote-carousel" class="right carousel-control"><i class="fa fa-chevron-right"></i></a>
			</div>
		</div>
	</div>
</div>';
