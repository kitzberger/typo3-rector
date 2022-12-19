<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TypeInferer\ParamTypeInferer;

use PhpParser\Node;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\NodeNameResolver\NodeNameResolver;

final class FunctionLikeDocParamTypeInferer
{
    /**
     * @readonly
     */
    private NodeNameResolver $nodeNameResolver;

    /**
     * @readonly
     */
    private PhpDocInfoFactory $phpDocInfoFactory;

    /**
     * @readonly
     */
    private BetterNodeFinder $betterNodeFinder;

    public function __construct(
        NodeNameResolver $nodeNameResolver,
        PhpDocInfoFactory $phpDocInfoFactory,
        BetterNodeFinder $betterNodeFinder
    ) {
        $this->nodeNameResolver = $nodeNameResolver;
        $this->phpDocInfoFactory = $phpDocInfoFactory;
        $this->betterNodeFinder = $betterNodeFinder;
    }

    public function inferParam(Param $param): Type
    {
        $functionLike = $this->resolveScopeNode($param);
        if (! $functionLike instanceof FunctionLike) {
            return new MixedType();
        }
        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($functionLike);
        $paramTypesByName = $phpDocInfo->getParamTypesByName();
        if ([] === $paramTypesByName) {
            return new MixedType();
        }
        return $this->matchParamNodeFromDoc($paramTypesByName, $param);
    }

    /**
     * @return ClassMethod|Function_|null
     */
    private function resolveScopeNode(Param $param): ?Node
    {
        return $this->betterNodeFinder->findParentByTypes($param, [ClassMethod::class, Function_::class]);
    }

    /**
     * @param Type[] $paramWithTypes
     */
    private function matchParamNodeFromDoc(array $paramWithTypes, Param $param): Type
    {
        $paramNodeName = '$' . $this->nodeNameResolver->getName($param->var);
        return $paramWithTypes[$paramNodeName] ?? new MixedType();
    }
}
