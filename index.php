<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WP_REST_API_Theme
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main" v-on:load="remove">

		<?php
		if ( have_posts() ) :

			if ( is_home() && ! is_front_page() ) : ?>
				<header>
					<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
				</header>

			<?php
			endif;

			/* Start the Loop */
			while ( have_posts() ) : the_post();

				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				get_template_part( 'template-parts/content', get_post_format() );

			endwhile;

			the_posts_navigation();

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif; ?>

		</main><!-- #main -->
		<main id="app" class="site-main" role="main">
			<router-view></router-view>
		</main><!-- #main -->
	</div><!-- #primary -->

	<template id="test">
		<div class="posts">
			<article v-for="post in posts" :post="post" v-bind="{ id: 'post-' + post.id, class: ['post-' + post.id, post.type, 'type-' + post.type, post.sticky == true ? 'sticky' : '', 'format-' + post.format, 'hentry'] }">
				<header class="entry-header">
					<h1 class="entry-title">
						<a :href="post.link" rel="bookmark">{{ post.title.rendered }}</a>
					</h1>
					<div class="entry-meta">
						<span class="posted-on">Posted on 
							<a :href="post.link" rel="bookmark">
								<time class="entry-date" :datetime="post.date_gmt">{{ post.date }}</time></a>
						</span>
						<span class="byline">by 
							<span class="author vcard">
								<a :href="post.author_posts_url">{{ post.author_name }}</a>
							</span>
						</span>
					</div>
				</header>
				<div class="entry-content" v-html="post.excerpt.rendered"></div>
				<footer class="entry-footer">
					<span v-for="category in post.category_list" class="cat-links">Posted in <a :href="'/category/' + category.slug + '/'" rel="category tag">{{ category.name }}</a></span>
					<span v-for="tag in post.tag_list" class="tags-links">Tagged <a :href="'/tag/' + tag.slug + '/'" rel="tag">{{ tag.name }}</a></span>
					<span class="comments-link">
						<a :href="post.link + '#comments'" v-if="post.comment_number == 1">{{ post.comment_number }} Comments <span class="screen-reader-text"> on {{ post.title.rendered }}</span></a>
						<a :href="post.link + '#respond'" v-else>Leave a Comment</a>
					</span>
				</footer>
			</article>
		</div>
	</template>
<?php
get_sidebar();
get_footer();
