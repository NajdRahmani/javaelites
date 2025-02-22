<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/_profiler' => [[['_route' => '_profiler_home', '_controller' => 'web_profiler.controller.profiler::homeAction'], null, null, null, true, false, null]],
        '/_profiler/search' => [[['_route' => '_profiler_search', '_controller' => 'web_profiler.controller.profiler::searchAction'], null, null, null, false, false, null]],
        '/_profiler/search_bar' => [[['_route' => '_profiler_search_bar', '_controller' => 'web_profiler.controller.profiler::searchBarAction'], null, null, null, false, false, null]],
        '/_profiler/phpinfo' => [[['_route' => '_profiler_phpinfo', '_controller' => 'web_profiler.controller.profiler::phpinfoAction'], null, null, null, false, false, null]],
        '/_profiler/xdebug' => [[['_route' => '_profiler_xdebug', '_controller' => 'web_profiler.controller.profiler::xdebugAction'], null, null, null, false, false, null]],
        '/_profiler/open' => [[['_route' => '_profiler_open_file', '_controller' => 'web_profiler.controller.profiler::openAction'], null, null, null, false, false, null]],
        '/blog' => [[['_route' => 'app_blog_index', '_controller' => 'App\\Controller\\BlogController::index'], null, ['GET' => 0], null, false, false, null]],
        '/blog/new' => [[['_route' => 'app_blog_new', '_controller' => 'App\\Controller\\BlogController::new'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/categorie' => [[['_route' => 'app_categorie_index', '_controller' => 'App\\Controller\\CategorieController::index'], null, ['GET' => 0], null, false, false, null]],
        '/categorie/new' => [[['_route' => 'app_categorie_new', '_controller' => 'App\\Controller\\CategorieController::new'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/chapitre' => [[['_route' => 'app_chapitre_index', '_controller' => 'App\\Controller\\ChapitreController::index'], null, ['GET' => 0], null, false, false, null]],
        '/comment' => [[['_route' => 'app_comment_index', '_controller' => 'App\\Controller\\CommentController::index'], null, ['GET' => 0], null, false, false, null]],
        '/comment/new' => [[['_route' => 'app_comment_new', '_controller' => 'App\\Controller\\CommentController::new'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/cours' => [[['_route' => 'app_cours_index', '_controller' => 'App\\Controller\\CoursController::index'], null, ['GET' => 0], null, false, false, null]],
        '/cours/cours' => [[['_route' => 'app_cours_index2', '_controller' => 'App\\Controller\\CoursController::index2'], null, ['GET' => 0], null, false, false, null]],
        '/cours/filter' => [[['_route' => 'app_cours_filter', '_controller' => 'App\\Controller\\CoursController::filter'], null, ['GET' => 0], null, false, false, null]],
        '/home' => [[['_route' => 'app_home_index', '_controller' => 'App\\Controller\\HomeController::index'], null, ['GET' => 0], null, false, false, null]],
        '/home/new' => [[['_route' => 'app_home', '_controller' => 'App\\Controller\\HomeController::new'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/like' => [[['_route' => 'app_like_index', '_controller' => 'App\\Controller\\LikeController::index'], null, ['GET' => 0], null, false, false, null]],
        '/like/new' => [[['_route' => 'app_like_new', '_controller' => 'App\\Controller\\LikeController::new'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/matiere' => [[['_route' => 'app_matiere_index', '_controller' => 'App\\Controller\\MatiereController::index'], null, ['GET' => 0], null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_(?'
                    .'|error/(\\d+)(?:\\.([^/]++))?(*:38)'
                    .'|wdt/([^/]++)(*:57)'
                    .'|profiler/(?'
                        .'|font/([^/\\.]++)\\.woff2(*:98)'
                        .'|([^/]++)(?'
                            .'|/(?'
                                .'|search/results(*:134)'
                                .'|router(*:148)'
                                .'|exception(?'
                                    .'|(*:168)'
                                    .'|\\.css(*:181)'
                                .')'
                            .')'
                            .'|(*:191)'
                        .')'
                    .')'
                .')'
                .'|/blog/([^/]++)(?'
                    .'|(*:219)'
                    .'|/edit(*:232)'
                    .'|(*:240)'
                .')'
                .'|/c(?'
                    .'|ategorie/([^/]++)(?'
                        .'|(*:274)'
                        .'|/edit(*:287)'
                        .'|(*:295)'
                    .')'
                    .'|hapitre/(?'
                        .'|etu(?'
                            .'|/([^/]++)(*:330)'
                            .'|diant/([^/]++)(*:352)'
                        .')'
                        .'|new/([^/]++)(*:373)'
                        .'|([^/]++)(?'
                            .'|(*:392)'
                            .'|/edit(*:405)'
                            .'|(*:413)'
                        .')'
                    .')'
                    .'|o(?'
                        .'|mment/([^/]++)(?'
                            .'|(*:444)'
                            .'|/edit(*:457)'
                            .'|(*:465)'
                        .')'
                        .'|urs/(?'
                            .'|coursenseignant/([^/]++)(*:505)'
                            .'|enseignant/([^/]++)(*:532)'
                            .'|([^/]++)(*:548)'
                            .'|new/([^/]++)(*:568)'
                            .'|([^/]++)(?'
                                .'|/edit(*:592)'
                                .'|(*:600)'
                            .')'
                        .')'
                    .')'
                .')'
                .'|/home/([^/]++)(?'
                    .'|(*:629)'
                    .'|/edit(*:642)'
                    .'|(*:650)'
                .')'
                .'|/like/([^/]++)(?'
                    .'|(*:676)'
                    .'|/edit(*:689)'
                    .'|(*:697)'
                .')'
                .'|/matiere/(?'
                    .'|new/([^/]++)(*:730)'
                    .'|([^/]++)(?'
                        .'|(*:749)'
                        .'|/edit(*:762)'
                        .'|(*:770)'
                    .')'
                .')'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        38 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        57 => [[['_route' => '_wdt', '_controller' => 'web_profiler.controller.profiler::toolbarAction'], ['token'], null, null, false, true, null]],
        98 => [[['_route' => '_profiler_font', '_controller' => 'web_profiler.controller.profiler::fontAction'], ['fontName'], null, null, false, false, null]],
        134 => [[['_route' => '_profiler_search_results', '_controller' => 'web_profiler.controller.profiler::searchResultsAction'], ['token'], null, null, false, false, null]],
        148 => [[['_route' => '_profiler_router', '_controller' => 'web_profiler.controller.router::panelAction'], ['token'], null, null, false, false, null]],
        168 => [[['_route' => '_profiler_exception', '_controller' => 'web_profiler.controller.exception_panel::body'], ['token'], null, null, false, false, null]],
        181 => [[['_route' => '_profiler_exception_css', '_controller' => 'web_profiler.controller.exception_panel::stylesheet'], ['token'], null, null, false, false, null]],
        191 => [[['_route' => '_profiler', '_controller' => 'web_profiler.controller.profiler::panelAction'], ['token'], null, null, false, true, null]],
        219 => [[['_route' => 'app_blog_show', '_controller' => 'App\\Controller\\BlogController::show'], ['id'], ['GET' => 0], null, false, true, null]],
        232 => [[['_route' => 'app_blog_edit', '_controller' => 'App\\Controller\\BlogController::edit'], ['id'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        240 => [[['_route' => 'app_blog_delete', '_controller' => 'App\\Controller\\BlogController::delete'], ['id'], ['POST' => 0], null, false, true, null]],
        274 => [[['_route' => 'app_categorie_show', '_controller' => 'App\\Controller\\CategorieController::show'], ['id'], ['GET' => 0], null, false, true, null]],
        287 => [[['_route' => 'app_categorie_edit', '_controller' => 'App\\Controller\\CategorieController::edit'], ['id'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        295 => [[['_route' => 'app_categorie_delete', '_controller' => 'App\\Controller\\CategorieController::delete'], ['id'], ['POST' => 0], null, false, true, null]],
        330 => [[['_route' => 'app_chapitre_indexEtu', '_controller' => 'App\\Controller\\ChapitreController::indexetu'], ['id'], ['GET' => 0], null, false, true, null]],
        352 => [[['_route' => 'app_etudiant', '_controller' => 'App\\Controller\\ChapitreController::showEtu'], ['id'], ['GET' => 0], null, false, true, null]],
        373 => [[['_route' => 'app_chapitre_new', '_controller' => 'App\\Controller\\ChapitreController::new'], ['id'], ['GET' => 0, 'POST' => 1], null, false, true, null]],
        392 => [[['_route' => 'app_chapitre_show', '_controller' => 'App\\Controller\\ChapitreController::show'], ['id'], ['GET' => 0], null, false, true, null]],
        405 => [[['_route' => 'app_chapitre_edit', '_controller' => 'App\\Controller\\ChapitreController::edit'], ['id'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        413 => [[['_route' => 'app_chapitre_delete', '_controller' => 'App\\Controller\\ChapitreController::delete'], ['id'], ['POST' => 0], null, false, true, null]],
        444 => [[['_route' => 'app_comment_show', '_controller' => 'App\\Controller\\CommentController::show'], ['id'], ['GET' => 0], null, false, true, null]],
        457 => [[['_route' => 'app_comment_edit', '_controller' => 'App\\Controller\\CommentController::edit'], ['id'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        465 => [[['_route' => 'app_comment_delete', '_controller' => 'App\\Controller\\CommentController::delete'], ['id'], ['POST' => 0], null, false, true, null]],
        505 => [[['_route' => 'coursEnseignant', '_controller' => 'App\\Controller\\CoursController::index1'], ['id'], ['GET' => 0], null, false, true, null]],
        532 => [[['_route' => 'app_cours', '_controller' => 'App\\Controller\\CoursController::show1'], ['id'], ['GET' => 0], null, false, true, null]],
        548 => [[['_route' => 'app_cours_show', '_controller' => 'App\\Controller\\CoursController::show'], ['id'], ['GET' => 0], null, false, true, null]],
        568 => [[['_route' => 'app_cours_new', '_controller' => 'App\\Controller\\CoursController::new'], ['id'], ['GET' => 0, 'POST' => 1], null, false, true, null]],
        592 => [[['_route' => 'app_cours_edit', '_controller' => 'App\\Controller\\CoursController::edit'], ['id'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        600 => [[['_route' => 'app_cours_delete', '_controller' => 'App\\Controller\\CoursController::delete'], ['id'], ['POST' => 0], null, false, true, null]],
        629 => [[['_route' => 'app_home_show', '_controller' => 'App\\Controller\\HomeController::show'], ['id'], ['GET' => 0], null, false, true, null]],
        642 => [[['_route' => 'app_home_edit', '_controller' => 'App\\Controller\\HomeController::edit'], ['id'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        650 => [[['_route' => 'app_home_delete', '_controller' => 'App\\Controller\\HomeController::delete'], ['id'], ['POST' => 0], null, false, true, null]],
        676 => [[['_route' => 'app_like_show', '_controller' => 'App\\Controller\\LikeController::show'], ['id'], ['GET' => 0], null, false, true, null]],
        689 => [[['_route' => 'app_like_edit', '_controller' => 'App\\Controller\\LikeController::edit'], ['id'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        697 => [[['_route' => 'app_like_delete', '_controller' => 'App\\Controller\\LikeController::delete'], ['id'], ['POST' => 0], null, false, true, null]],
        730 => [[['_route' => 'app_matiere_new', '_controller' => 'App\\Controller\\MatiereController::new'], ['id'], ['GET' => 0, 'POST' => 1], null, false, true, null]],
        749 => [[['_route' => 'app_matiere_show', '_controller' => 'App\\Controller\\MatiereController::show'], ['id'], ['GET' => 0], null, false, true, null]],
        762 => [[['_route' => 'app_matiere_edit', '_controller' => 'App\\Controller\\MatiereController::edit'], ['id'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        770 => [
            [['_route' => 'app_matiere_delete', '_controller' => 'App\\Controller\\MatiereController::delete'], ['id'], ['POST' => 0], null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
