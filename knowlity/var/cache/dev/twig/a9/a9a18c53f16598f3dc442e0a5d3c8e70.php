<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;
use Twig\TemplateWrapper;

/* cours/show.html.twig */
class __TwigTemplate_7ce811fa3f52f3252e3418993788e2e5 extends Template
{
    private Source $source;
    /**
     * @var array<string, Template>
     */
    private array $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'title' => [$this, 'block_title'],
            'stylesheets' => [$this, 'block_stylesheets'],
            'body' => [$this, 'block_body'],
        ];
    }

    protected function doGetParent(array $context): bool|string|Template|TemplateWrapper
    {
        // line 1
        return "base.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "cours/show.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "cours/show.html.twig"));

        $this->parent = $this->loadTemplate("base.html.twig", "cours/show.html.twig", 1);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 3
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_title(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "title"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "title"));

        yield "Cours";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    // line 4
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_stylesheets(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "stylesheets"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "stylesheets"));

        // line 5
        yield "        <link rel=\"stylesheet\" href=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("assets/css/chapitre1.css"), "html", null, true);
        yield "\">

";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    // line 8
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_body(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "body"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "body"));

        // line 9
        yield "    
<section class=\"content\">
<a href=\"";
        // line 11
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_cours_index");
        yield "\">back to list</a>
<h1>Cours</h1>
      <!-- Default box -->
      <div class=\"card card-solid\">
        <div class=\"card-body\">
          <div class=\"row\">
            <div class=\"col-12 col-sm-6\">
              <h3 class=\"d-inline-block d-sm-none\">";
        // line 18
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (isset($context["cour"]) || array_key_exists("cour", $context) ? $context["cour"] : (function () { throw new RuntimeError('Variable "cour" does not exist.', 18, $this->source); })()), "title", [], "any", false, false, false, 18), "html", null, true);
        yield "</h3>
              <div class=\"col-12 imageCours\">
                <img src='";
        // line 20
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl(("uploads/brochures/" . CoreExtension::getAttribute($this->env, $this->source, (isset($context["cour"]) || array_key_exists("cour", $context) ? $context["cour"] : (function () { throw new RuntimeError('Variable "cour" does not exist.', 20, $this->source); })()), "urlImage", [], "any", false, false, false, 20))), "html", null, true);
        yield "' class=\"product-image\" alt=\"Product Image\">
              </div>
              <div class=\"col-12 product-image-thumbs\">
                 </div>
            </div>
            <div class=\"col-12 col-sm-6\">
              <h3 class=\"my-3\">";
        // line 26
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (isset($context["cour"]) || array_key_exists("cour", $context) ? $context["cour"] : (function () { throw new RuntimeError('Variable "cour" does not exist.', 26, $this->source); })()), "title", [], "any", false, false, false, 26), "html", null, true);
        yield "</h3>
               <div class=\"mt-4 product-share\">
               ";
        // line 28
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["cour"]) || array_key_exists("cour", $context) ? $context["cour"] : (function () { throw new RuntimeError('Variable "cour" does not exist.', 28, $this->source); })()), "matiere", [], "any", false, false, false, 28), "titre", [], "any", false, false, false, 28), "html", null, true);
        yield "
              </div>
              <p>";
        // line 30
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (isset($context["cour"]) || array_key_exists("cour", $context) ? $context["cour"] : (function () { throw new RuntimeError('Variable "cour" does not exist.', 30, $this->source); })()), "description", [], "any", false, false, false, 30), "html", null, true);
        yield "</p>

              <hr>
              <h4>";
        // line 33
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (isset($context["cour"]) || array_key_exists("cour", $context) ? $context["cour"] : (function () { throw new RuntimeError('Variable "cour" does not exist.', 33, $this->source); })()), "nbChapitres", [], "any", false, false, false, 33), "html", null, true);
        yield " chapitres</h4>
              

              
              <div class=\"bg-gray py-2 px-3 mt-4\">
                <h2 class=\"mb-0\">
                  80.00 DT
                </h2>
                <h4 class=\"mt-0\">
                  
                </h4>
              </div>

              <div class=\"mt-4\">
               <div class=\"btn btn-primary btn-lg\">
    S'inscrire
</div>


                
              </div>

             

            </div>
          </div>
          <div class=\"row mt-4\">
            <nav class=\"w-100\">
              <div class=\"nav nav-tabs\" id=\"product-tab\" role=\"tablist\">
                <a class=\"nav-item nav-link active\" id=\"product-desc-tab\" data-toggle=\"tab\" href=\"#product-desc\" role=\"tab\" aria-controls=\"product-desc\" aria-selected=\"true\">Description</a>
               </div>
            </nav>
            <div class=\"tab-content p-3\" id=\"nav-tabContent\">
              <div class=\"tab-pane fade show active\" id=\"product-desc\" role=\"tabpanel\" aria-labelledby=\"product-desc-tab\">";
        // line 66
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (isset($context["cour"]) || array_key_exists("cour", $context) ? $context["cour"] : (function () { throw new RuntimeError('Variable "cour" does not exist.', 66, $this->source); })()), "urlImage", [], "any", false, false, false, 66), "html", null, true);
        yield " </div>
          </div>
          </div>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->

    </section>
    <h1>Liste des chapitres</h1>

    <div class=\"demandes-container\">
        ";
        // line 78
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, (isset($context["cour"]) || array_key_exists("cour", $context) ? $context["cour"] : (function () { throw new RuntimeError('Variable "cour" does not exist.', 78, $this->source); })()), "chapitres", [], "any", false, false, false, 78));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["chapitre"]) {
            // line 79
            yield "            <div class=\"demande-card\">
                <div class=\"demande-info\">
                    <p> </p>
                    <p><strong></strong></p>
                    <p><strong>Title:</strong> ";
            // line 83
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["chapitre"], "title", [], "any", false, false, false, 83), "html", null, true);
            yield "</p>
                    <p><strong>ChapOrder:</strong> ";
            // line 84
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["chapitre"], "chapOrder", [], "any", false, false, false, 84), "html", null, true);
            yield "</p>
                    <p><strong>actions:</strong></p>

                </div>
                <div class=\"demande-actions\">
                 <a href=\"";
            // line 89
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_etudiant", ["id" => CoreExtension::getAttribute($this->env, $this->source, $context["chapitre"], "id", [], "any", false, false, false, 89)]), "html", null, true);
            yield "\" class=\"btn btn-propose\">show</a>
                    
                </div>
            </div>
        ";
            $context['_iterated'] = true;
        }
        // line 97
        if (!$context['_iterated']) {
            // line 94
            yield "            <div class=\"no-records\">
                Aucune demande trouvée.
            </div>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['chapitre'], $context['_parent'], $context['_iterated']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 98
        yield "    </div>

    

  
";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "cours/show.html.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable(): bool
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  267 => 98,  258 => 94,  256 => 97,  247 => 89,  239 => 84,  235 => 83,  229 => 79,  224 => 78,  209 => 66,  173 => 33,  167 => 30,  162 => 28,  157 => 26,  148 => 20,  143 => 18,  133 => 11,  129 => 9,  116 => 8,  101 => 5,  88 => 4,  65 => 3,  42 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends 'base.html.twig' %}

{% block title %}Cours{% endblock %}
{% block stylesheets %}
        <link rel=\"stylesheet\" href=\"{{ asset('assets/css/chapitre1.css') }}\">

{% endblock %}
{% block body %}
    
<section class=\"content\">
<a href=\"{{ path('app_cours_index') }}\">back to list</a>
<h1>Cours</h1>
      <!-- Default box -->
      <div class=\"card card-solid\">
        <div class=\"card-body\">
          <div class=\"row\">
            <div class=\"col-12 col-sm-6\">
              <h3 class=\"d-inline-block d-sm-none\">{{ cour.title }}</h3>
              <div class=\"col-12 imageCours\">
                <img src='{{ asset('uploads/brochures/' ~ cour.urlImage) }}' class=\"product-image\" alt=\"Product Image\">
              </div>
              <div class=\"col-12 product-image-thumbs\">
                 </div>
            </div>
            <div class=\"col-12 col-sm-6\">
              <h3 class=\"my-3\">{{ cour.title }}</h3>
               <div class=\"mt-4 product-share\">
               {{ cour.matiere.titre }}
              </div>
              <p>{{ cour.description }}</p>

              <hr>
              <h4>{{ cour.nbChapitres }} chapitres</h4>
              

              
              <div class=\"bg-gray py-2 px-3 mt-4\">
                <h2 class=\"mb-0\">
                  80.00 DT
                </h2>
                <h4 class=\"mt-0\">
                  
                </h4>
              </div>

              <div class=\"mt-4\">
               <div class=\"btn btn-primary btn-lg\">
    S'inscrire
</div>


                
              </div>

             

            </div>
          </div>
          <div class=\"row mt-4\">
            <nav class=\"w-100\">
              <div class=\"nav nav-tabs\" id=\"product-tab\" role=\"tablist\">
                <a class=\"nav-item nav-link active\" id=\"product-desc-tab\" data-toggle=\"tab\" href=\"#product-desc\" role=\"tab\" aria-controls=\"product-desc\" aria-selected=\"true\">Description</a>
               </div>
            </nav>
            <div class=\"tab-content p-3\" id=\"nav-tabContent\">
              <div class=\"tab-pane fade show active\" id=\"product-desc\" role=\"tabpanel\" aria-labelledby=\"product-desc-tab\">{{ cour.urlImage }} </div>
          </div>
          </div>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->

    </section>
    <h1>Liste des chapitres</h1>

    <div class=\"demandes-container\">
        {% for chapitre in cour.chapitres %}
            <div class=\"demande-card\">
                <div class=\"demande-info\">
                    <p> </p>
                    <p><strong></strong></p>
                    <p><strong>Title:</strong> {{ chapitre.title }}</p>
                    <p><strong>ChapOrder:</strong> {{ chapitre.chapOrder }}</p>
                    <p><strong>actions:</strong></p>

                </div>
                <div class=\"demande-actions\">
                 <a href=\"{{ path('app_etudiant', {'id': chapitre.id}) }}\" class=\"btn btn-propose\">show</a>
                    
                </div>
            </div>
        {% else %}
            <div class=\"no-records\">
                Aucune demande trouvée.
            </div>
        {% endfor %}
    </div>

    

  
{% endblock %}
", "cours/show.html.twig", "C:\\Users\\chams\\OneDrive\\Bureau\\projetPIDEV\\javaelites\\knowlity\\templates\\cours\\show.html.twig");
    }
}
