<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>{$lang.title}</title>
</head>
<body>
	{if $s_show}
		<h2>{$data.title}</h2>
		<p>{$data.author} | {$data.date}</p>
	{else}
		<h2>{$lang.body}</h2>
		<p>{$lang.test}</p>
		<ul>
			{foreach $data as $news}
			<li><a href="{$news.url}">{$news.title}</a></li>
			{/foreach}
		</ul>
	{/if}
</body>
</html>