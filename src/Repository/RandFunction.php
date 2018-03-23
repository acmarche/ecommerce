<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 19/02/18
 * Time: 10:35
 */

namespace App\Repository;

use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;

/**
 * Ajouter une fonction de random pour les select
 * RandFunction ::= "RAND" "(" ")"
 */
class RandFunction extends FunctionNode
{
    public function parse(Parser $parser)
    {
        try {
            $parser->match(Lexer::T_IDENTIFIER);
            $parser->match(Lexer::T_OPEN_PARENTHESIS);
            $parser->match(Lexer::T_CLOSE_PARENTHESIS);
        } catch (QueryException $e) {
        }

    }

    public function getSql(SqlWalker $sqlWalker)
    {
        return 'RAND()';
    }
}