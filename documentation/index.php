<?php

function get_sidebar_items() {
    $structure = [
        'Getting Started' => ['getting-started'],
        'Core Concepts' => ['configuration', 'redirection', 'router', 'routing'],
        'Features' => [
            'security',
            'error-handling',
            'session-and-cookie',
            'flash',
            'file',
            'helpers',
            'fetch',
            'json-web-token',
            'mailing',
            'database',
            'authentication',
        ],
        'Example' => ['basic-app', 'auth-app'],
    ];

    $items = [];
    foreach ($structure as $category => $pages) {
        $items[$category] = [];
        foreach ($pages as $page) {
            $items[$category][] = [
                'slug' => $page,
                'title' => ucwords(str_replace('-', ' ', $page))
            ];
        }
    }
    return $items;
}

function get_markdown_content($filename) {
    $markdown_path = __DIR__ . '/markdown/' . $filename . '.md';
    if (!file_exists($markdown_path)) {
        return "Documentation page not found.";
    }
    return file_get_contents($markdown_path);
}

function get_navigation($current_page, $items) {
    $all_pages = [];
    foreach ($items as $category => $pages) {
        $all_pages = array_merge($all_pages, $pages);
    }
    $current_index = array_search($current_page, array_column($all_pages, 'slug'));
    return [
        'prev' => $current_index > 0 ? $all_pages[$current_index - 1] : null,
        'next' => $current_index < count($all_pages) - 1 ? $all_pages[$current_index + 1] : null
    ];
}


$page = isset($_GET['page']) ? $_GET['page'] : 'getting-started';
$markdown_content = get_markdown_content($page);
$sidebar_items = get_sidebar_items();
$navigation = get_navigation($page, $sidebar_items);
?>

<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentation</title>
    <script src="https://cdn.tailwindcss.com?plugins=typography"></script>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/default.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script>
    tailwind.config = {
        darkMode: 'class',
        theme: {
            extend: {
                typography: {
                    DEFAULT: {
                        css: {
                            maxWidth: '100%',
                            color: '#d4d4d8',
                            h1: { color: '#fafafa' },
                            h2: { color: '#fafafa' },
                            h3: { color: '#fafafa' },
                            a: { color: '#60a5fa' },
                            code: { color: '#d4d4d8' },
                        },
                    },
                },
            },
        },
    }
    </script>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Parkinsans:wght@300..800&display=swap');
    * {
        font-family: 'Parkinsans', 'sans-serif';
    }
    </style>
</head>
<body class="bg-zinc-950 min-h-screen py-8">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="flex gap-6">
            <!-- Sidebar -->
            <aside class="w-72 shrink-0 sticky top-8 self-start h-[calc(100vh-4rem)]">
                <div class="rounded-lg border border-zinc-700 bg-zinc-900 h-full flex flex-col">
                    <div class="p-4 border-b border-zinc-700">
                        <h1 class="text-xl font-bold text-white">Documentation</h1>
                    </div>
                    <nav class="p-4 overflow-y-auto flex-1">
                        <?php foreach ($sidebar_items as $category => $items): ?>
                            <h2 class="text-sm font-semibold text-zinc-400 uppercase tracking-wider mb-2 mt-4"><?= $category ?></h2>
                            <?php foreach ($items as $item): ?>
                                <a href="?page=<?= $item['slug'] ?>" 
                                   class="block py-2 px-4 mb-1 rounded-lg transition-colors
                                          <?= $page === $item['slug'] 
                                            ? 'bg-zinc-700 text-white font-medium border border-zinc-600' 
                                            : 'text-zinc-300 hover:bg-zinc-700' ?>">
                                    <?= $item['title'] ?>
                                </a>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </nav>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 min-w-0">
                <div class="rounded-lg border border-zinc-700 bg-zinc-900">
                    <article class="p-8">
                        <div id="content" class="prose prose-invert prose-zinc prose-headings:font-semibold prose-a:text-blue-400 max-w-none">
                            <!-- Content will be inserted here -->
                        </div>
                    </article>

                    <!-- Navigation -->
                    <div class="sticky bottom-0 border-t border-zinc-700 p-4 bg-zinc-900 rounded-b-lg">
                        <div class="flex justify-between items-center max-w-screen-lg mx-auto">
                            <?php if ($navigation['prev']): ?>
                            <a href="?page=<?= $navigation['prev']['slug'] ?>" 
                               class="inline-flex items-center px-4 py-2 rounded-lg transition-colors
                                      border border-zinc-700
                                      text-zinc-300
                                      hover:bg-zinc-700">
                                ← <?= $navigation['prev']['title'] ?>
                            </a>
                            <?php else: ?>
                            <div></div>
                            <?php endif; ?>

                            <?php if ($navigation['next']): ?>
                            <a href="?page=<?= $navigation['next']['slug'] ?>" 
                               class="inline-flex items-center px-4 py-2 rounded-lg transition-colors
                                      border border-zinc-700
                                      text-zinc-300
                                      hover:bg-zinc-700">
                                <?= $navigation['next']['title'] ?> →
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Markdown Configuration
        marked.setOptions({
            gfm: true,
            breaks: true,
            headerIds: true,
            highlight: function(code, lang) {
                if (lang && hljs.getLanguage(lang)) {
                    try {
                        return hljs.highlight(code, { language: lang }).value;
                    } catch (err) {}
                }
                return code;
            }
        });

        const content = <?= json_encode($markdown_content) ?>;
        document.getElementById('content').innerHTML = marked.parse(content);

        // Initialize highlight.js
        document.querySelectorAll('pre code').forEach((block) => {
            hljs.highlightBlock(block);
            block.parentElement.classList.add(
                'border',
                'border-zinc-700',
                'rounded-lg',
                'bg-zinc-950',
                'p-0'
            );
            block.classList.add(
                'block',
                'p-4',
                'overflow-x-auto'
            );
        });

        // Style inline code
        document.querySelectorAll('code:not(pre code)').forEach((inline) => {
            inline.classList.add(
                'bg-zinc-950',
                'text-zinc-300',
                'px-1.5',
                'py-0.5',
                'rounded',
                'border',
                'border-zinc-700'
            );
        });

    </script>
</body>
</html>
