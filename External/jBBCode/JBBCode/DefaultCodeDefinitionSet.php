<?php

namespace JBBCode;

require_once 'CodeDefinition.php';
require_once 'CodeDefinitionBuilder.php';
require_once 'CodeDefinitionSet.php';
require_once 'validators/CssColorValidator.php';
require_once 'validators/UrlValidator.php';

class JBBCodeEmailValidator implements \JBBCode\InputValidator {

    public function validate($input)
    {
        $valid = filter_var($input, FILTER_VALIDATE_EMAIL);
        return !!$valid;
    }
}

/**
 * Provides a default set of common bbcode definitions.
 *
 * @author jbowens
 */
class DefaultCodeDefinitionSet implements CodeDefinitionSet
{

    /* The default code definitions in this set. */
    protected $definitions = array();
	
    /**
     * Constructs the default code definitions.
     */
    public function __construct()
    {
		// Validators
        $urlValidator = new \JBBCode\validators\UrlValidator();
        $cssValidator = new \JBBCode\validators\CssColorValidator();
        $emailValidator = new JBBCodeEmailValidator();

		// [h1]
        $builder = new \JBBCode\CodeDefinitionBuilder('h1', '<span style="font-size: 24px;">{param}</span>');
        array_push($this->definitions, $builder->build());
		
		// [h2]
        $builder = new \JBBCode\CodeDefinitionBuilder('h2', '<span style="font-size: 18px;">{param}</span>');
        array_push($this->definitions, $builder->build());
		
		// [h3]
        $builder = new \JBBCode\CodeDefinitionBuilder('h3', '<span style="font-size: 12px;">{param}</span>');
        array_push($this->definitions, $builder->build());
		
		// [h4]
        $builder = new \JBBCode\CodeDefinitionBuilder('h4', '<span style="font-size: 6px;">{param}</span>');
        array_push($this->definitions, $builder->build());
		
        // [b]
        $builder = new \JBBCode\CodeDefinitionBuilder('b', '<strong>{param}</strong>');
        array_push($this->definitions, $builder->build());

        // [i]
        $builder = new \JBBCode\CodeDefinitionBuilder('i', '<em>{param}</em>');
        array_push($this->definitions, $builder->build());

        // [u]
        $builder = new \JBBCode\CodeDefinitionBuilder('u', '<span style="text-decoration: underline">{param}</span>');
        array_push($this->definitions, $builder->build());

        // [s]
        $builder = new \JBBCode\CodeDefinitionBuilder('s', '<span style="text-decoration: line-through">{param}</span>');
        array_push($this->definitions, $builder->build());

        // [sub]
        $builder = new \JBBCode\CodeDefinitionBuilder('sub', '<sub>{param}</sub>');
        array_push($this->definitions, $builder->build());

        // [sup]
        $builder = new \JBBCode\CodeDefinitionBuilder('sup', '<sup>{param}</sup>');
        array_push($this->definitions, $builder->build());

        // [left]
        $builder = new \JBBCode\CodeDefinitionBuilder('left', '<span style="text-align: left">{param}</span>');
        array_push($this->definitions, $builder->build());

        // [center]
        $builder = new \JBBCode\CodeDefinitionBuilder('center', '<span style="text-align: center">{param}</span>');
        array_push($this->definitions, $builder->build());

        // [right]
        $builder = new \JBBCode\CodeDefinitionBuilder('right', '<span style="text-align: right">{param}</span>');
        array_push($this->definitions, $builder->build());

        // [justify]
        $builder = new \JBBCode\CodeDefinitionBuilder('justify', '<span style="text-align: justify">{param}</span>');
        array_push($this->definitions, $builder->build());

        // [font]
        $builder = new \JBBCode\CodeDefinitionBuilder('font', '<span style="font-family: {option}">{param}</span>');
        $builder->setUseOption(true);
        $builder->setOptionValidator($cssValidator);
        array_push($this->definitions, $builder->build());

        // [size]
        $builder = new \JBBCode\CodeDefinitionBuilder('size', '<span style="font-size: {option}">{param}</span>');
        $builder->setUseOption(true);
        $builder->setOptionValidator($cssValidator);
        array_push($this->definitions, $builder->build());

        // [color]
        $builder = new \JBBCode\CodeDefinitionBuilder('color', '<span style="color: {option}">{param}</span>');
        $builder->setUseOption(true);
        $builder->setOptionValidator($cssValidator);
        array_push($this->definitions, $builder->build());

        // [li]
        $builder = new \JBBCode\CodeDefinitionBuilder('li', '<li>{param}</li>');
        array_push($this->definitions, $builder->build());

        // [ul]
        $builder = new \JBBCode\CodeDefinitionBuilder('ul', '<ul>{param}</ul>');
        array_push($this->definitions, $builder->build());

        // [ol]
        $builder = new \JBBCode\CodeDefinitionBuilder('ol', '<ol>{param}</ol>');
        array_push($this->definitions, $builder->build());

        // [table]
        $builder = new \JBBCode\CodeDefinitionBuilder('table', '<table border="1">{param}</table>');
        array_push($this->definitions, $builder->build());

        // [tr]
        $builder = new \JBBCode\CodeDefinitionBuilder('tr', '<tr>{param}</tr>');
        array_push($this->definitions, $builder->build());

        // [td]
        $builder = new \JBBCode\CodeDefinitionBuilder('td', '<td>{param}</td>');
        array_push($this->definitions, $builder->build());

        // [hr]
        $builder = new \JBBCode\CodeDefinitionBuilder('hr', '<hr />');
        array_push($this->definitions, $builder->build());

        // [code]
        $builder = new \JBBCode\CodeDefinitionBuilder('code', '<div class="code-box border-radius"><code>{param}</code></div>');
        array_push($this->definitions, $builder->build());

        // [img]
        $builder = new \JBBCode\CodeDefinitionBuilder('img', '<img src="{param}" />');
        $builder->setUseOption(false);
        $builder->setParseContent(false);
        $builder->setBodyValidator($urlValidator);
        array_push($this->definitions, $builder->build());

        $builder = new \JBBCode\CodeDefinitionBuilder('img', '<img src="{param}" style="{option}" />');
        $builder->setUseOption(true);
        $builder->setParseContent(false);
        $builder->setBodyValidator($urlValidator);
        array_push($this->definitions, $builder->build());

        // [email]
        $builder = new \JBBCode\CodeDefinitionBuilder('email', '<a href="mailto:{option}">{param}</a>');
        $builder->setUseOption(true);
        $builder->setParseContent(true);
        $builder->setOptionValidator($emailValidator);
        array_push($this->definitions, $builder->build());

        // [email] none content
        $builder = new \JBBCode\CodeDefinitionBuilder('email', '<a href="mailto:{param}">{param}</a>');
        $builder->setParseContent(false);
        array_push($this->definitions, $builder->build());

        // [url]
        $builder = new \JBBCode\CodeDefinitionBuilder('url', '<a href="{option}" target="_blank" class="sy-yellow">{param}</a>');
        $builder->setUseOption(true);
        $builder->setParseContent(true);
        array_push($this->definitions, $builder->build());

        // [url] none content
        $builder = new \JBBCode\CodeDefinitionBuilder('url', '<a href="{param}" target="_blank">{param}</a>');
        $builder->setParseContent(false);
        $builder->setBodyValidator($urlValidator);
        array_push($this->definitions, $builder->build());

        // [quote]
        $builder = new \JBBCode\CodeDefinitionBuilder('quote', '<blockquote class="border-radius">{param}</blockquote>');
        $builder->setUseOption(false);
        array_push($this->definitions, $builder->build());

        // [quote] cite
        $builder = new \JBBCode\CodeDefinitionBuilder('quote', '<blockquote class="border-radius" cite="{option}">{param}</blockquote>');
        $builder->setUseOption(true);
        $builder->setOptionValidator($urlValidator);
        array_push($this->definitions, $builder->build());

        // [youtube]
        $builder = new \JBBCode\CodeDefinitionBuilder('youtube', '<iframe class="youtube_player_emb" src="https://www.youtube.com/embed/{param}" width="507" height="285" allowfullscreen="" frameborder="0"></iframe>');
        array_push($this->definitions, $builder->build());

        // [rtl]
        $builder = new \JBBCode\CodeDefinitionBuilder('rtl', '<span style="direction: rtl">{param}</span>');
        array_push($this->definitions, $builder->build());

        // [ltr]
        $builder = new \JBBCode\CodeDefinitionBuilder('ltr', '<span style="direction: ltr">{param}</span>');
        array_push($this->definitions, $builder->build());
		
		// New BBCode [item][/item]
		$builder = new CodeDefinitionBuilder('item', '<a href="https://vanilla-twinhead.twinstar.cz/?item={param}" rel="item={param}" target="_blank">{param}</a>');
		array_push($this->definitions, $builder->build());
		
		// New BBCode [item][/item]
		$builder = new CodeDefinitionBuilder('item', '<a href="https://vanilla-twinhead.twinstar.cz/?item={param}" rel="item={option}" target="_blank">{param}</a>');
		$builder->setUseOption(true);
		array_push($this->definitions, $builder->build());
		
		// New BBCode [item][/item]
		$builder = new CodeDefinitionBuilder('spell', '<a href="https://vanilla-twinhead.twinstar.cz/?spell={param}" rel="spell={param}" target="_blank">{param}</a>');
		array_push($this->definitions, $builder->build());
		
		// New BBCode [item][/item]
		$builder = new CodeDefinitionBuilder('spell', '<a href="https://vanilla-twinhead.twinstar.cz/?spell={param}" rel="spell={option}" target="_blank">{param}</a>');
		$builder->setUseOption(true);
		array_push($this->definitions, $builder->build());
		
		// [emoticon]
		$builder = new \JBBCode\CodeDefinitionBuilder('emoticon', '<img src="{path}External/SCEditor/emoticons/{param}.png" />');
        array_push($this->definitions, $builder->build());
    }

    /**
     * Returns an array of the default code definitions.
     */
    public function getCodeDefinitions() 
    {
        return $this->definitions;
    }

}
