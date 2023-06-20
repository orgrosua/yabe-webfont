<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7af963c208bee77414c8dd2a06772197
{
    public static $files = array (
        'a4a119a56e50fbb293281d9a48007e0e' => __DIR__ . '/..' . '/symfony/polyfill-php80/bootstrap.php',
        '6e3fae29631ef280660b3cdad06f25a8' => __DIR__ . '/..' . '/symfony/deprecation-contracts/function.php',
        '320cde22f66dd4f5d3fd621d3e88b98f' => __DIR__ . '/..' . '/symfony/polyfill-ctype/bootstrap.php',
        '8825ede83f2f289127722d4e842cf7e8' => __DIR__ . '/..' . '/symfony/polyfill-intl-grapheme/bootstrap.php',
        'e69f7f6ee287b969198c3c9d6777bd38' => __DIR__ . '/..' . '/symfony/polyfill-intl-normalizer/bootstrap.php',
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
        'b6b991a57620e2fb6b2f66f03fe9ddc2' => __DIR__ . '/..' . '/symfony/string/Resources/functions.php',
        'fa9c7a129d9efc034fd91b08d4905857' => __DIR__ . '/../..' . '/constant.php',
    );

    public static $prefixLengthsPsr4 = array (
        '_' => 
        array (
            '_YabeWebfont\\Symfony\\Polyfill\\Php80\\' => 36,
            '_YabeWebfont\\Symfony\\Polyfill\\Mbstring\\' => 39,
            '_YabeWebfont\\Symfony\\Polyfill\\Intl\\Normalizer\\' => 46,
            '_YabeWebfont\\Symfony\\Polyfill\\Intl\\Grapheme\\' => 44,
            '_YabeWebfont\\Symfony\\Polyfill\\Ctype\\' => 36,
            '_YabeWebfont\\Symfony\\Contracts\\Service\\' => 39,
            '_YabeWebfont\\Symfony\\Component\\String\\' => 38,
            '_YabeWebfont\\Symfony\\Component\\Stopwatch\\' => 41,
            '_YabeWebfont\\Symfony\\Component\\PropertyInfo\\' => 44,
            '_YabeWebfont\\Symfony\\Component\\PropertyAccess\\' => 46,
            '_YabeWebfont\\Symfony\\Component\\Finder\\' => 38,
            '_YabeWebfont\\Rosua\\Migrations\\' => 30,
            '_YabeWebfont\\Psr\\Container\\' => 27,
            '_YabeWebfont\\Hidehalo\\Nanoid\\' => 29,
        ),
        'Y' => 
        array (
            'Yabe\\Webfont\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        '_YabeWebfont\\Symfony\\Polyfill\\Php80\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-php80',
        ),
        '_YabeWebfont\\Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-mbstring',
        ),
        '_YabeWebfont\\Symfony\\Polyfill\\Intl\\Normalizer\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-intl-normalizer',
        ),
        '_YabeWebfont\\Symfony\\Polyfill\\Intl\\Grapheme\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-intl-grapheme',
        ),
        '_YabeWebfont\\Symfony\\Polyfill\\Ctype\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-ctype',
        ),
        '_YabeWebfont\\Symfony\\Contracts\\Service\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/service-contracts',
        ),
        '_YabeWebfont\\Symfony\\Component\\String\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/string',
        ),
        '_YabeWebfont\\Symfony\\Component\\Stopwatch\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/stopwatch',
        ),
        '_YabeWebfont\\Symfony\\Component\\PropertyInfo\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/property-info',
        ),
        '_YabeWebfont\\Symfony\\Component\\PropertyAccess\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/property-access',
        ),
        '_YabeWebfont\\Symfony\\Component\\Finder\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/finder',
        ),
        '_YabeWebfont\\Rosua\\Migrations\\' => 
        array (
            0 => __DIR__ . '/..' . '/rosua/migrations/src',
        ),
        '_YabeWebfont\\Psr\\Container\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/container/src',
        ),
        '_YabeWebfont\\Hidehalo\\Nanoid\\' => 
        array (
            0 => __DIR__ . '/..' . '/hidehalo/nanoid-php/src',
        ),
        'Yabe\\Webfont\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Yabe\\Webfont\\Admin\\AdminPage' => __DIR__ . '/../..' . '/src/Admin/AdminPage.php',
        'Yabe\\Webfont\\Api\\AbstractApi' => __DIR__ . '/../..' . '/src/Api/AbstractApi.php',
        'Yabe\\Webfont\\Api\\ApiInterface' => __DIR__ . '/../..' . '/src/Api/ApiInterface.php',
        'Yabe\\Webfont\\Api\\Font' => __DIR__ . '/../..' . '/src/Api/Font.php',
        'Yabe\\Webfont\\Api\\Router' => __DIR__ . '/../..' . '/src/Api/Router.php',
        'Yabe\\Webfont\\Api\\Setting\\AdobeFonts' => __DIR__ . '/../..' . '/src/Api/Setting/AdobeFonts.php',
        'Yabe\\Webfont\\Api\\Setting\\Cache' => __DIR__ . '/../..' . '/src/Api/Setting/Cache.php',
        'Yabe\\Webfont\\Api\\Setting\\License' => __DIR__ . '/../..' . '/src/Api/Setting/License.php',
        'Yabe\\Webfont\\Api\\Setting\\Option' => __DIR__ . '/../..' . '/src/Api/Setting/Option.php',
        'Yabe\\Webfont\\Builder\\BuilderInterface' => __DIR__ . '/../..' . '/src/Builder/BuilderInterface.php',
        'Yabe\\Webfont\\Builder\\ClassicEditor\\Main' => __DIR__ . '/../..' . '/src/Builder/ClassicEditor/Main.php',
        'Yabe\\Webfont\\Builder\\Elementor\\Main' => __DIR__ . '/../..' . '/src/Builder/Elementor/Main.php',
        'Yabe\\Webfont\\Builder\\GeneratePress\\Main' => __DIR__ . '/../..' . '/src/Builder/GeneratePress/Main.php',
        'Yabe\\Webfont\\Builder\\Gutenberg\\Main' => __DIR__ . '/../..' . '/src/Builder/Gutenberg/Main.php',
        'Yabe\\Webfont\\Builder\\Integration' => __DIR__ . '/../..' . '/src/Builder/Integration.php',
        'Yabe\\Webfont\\Builder\\Kadence\\Main' => __DIR__ . '/../..' . '/src/Builder/Kadence/Main.php',
        'Yabe\\Webfont\\Core\\Cache' => __DIR__ . '/../..' . '/src/Core/Cache.php',
        'Yabe\\Webfont\\Core\\Frontpage' => __DIR__ . '/../..' . '/src/Core/Frontpage.php',
        'Yabe\\Webfont\\Core\\Runtime' => __DIR__ . '/../..' . '/src/Core/Runtime.php',
        'Yabe\\Webfont\\Migration' => __DIR__ . '/../..' . '/src/Migration.php',
        'Yabe\\Webfont\\Plugin' => __DIR__ . '/../..' . '/src/Plugin.php',
        'Yabe\\Webfont\\Utils\\Common' => __DIR__ . '/../..' . '/src/Utils/Common.php',
        'Yabe\\Webfont\\Utils\\Config' => __DIR__ . '/../..' . '/src/Utils/Config.php',
        'Yabe\\Webfont\\Utils\\Debug' => __DIR__ . '/../..' . '/src/Utils/Debug.php',
        'Yabe\\Webfont\\Utils\\Font' => __DIR__ . '/../..' . '/src/Utils/Font.php',
        'Yabe\\Webfont\\Utils\\Notice' => __DIR__ . '/../..' . '/src/Utils/Notice.php',
        'Yabe\\Webfont\\Utils\\Upload' => __DIR__ . '/../..' . '/src/Utils/Upload.php',
        '_YabeWebfont\\Attribute' => __DIR__ . '/..' . '/symfony/polyfill-php80/Resources/stubs/Attribute.php',
        '_YabeWebfont\\Hidehalo\\Nanoid\\Client' => __DIR__ . '/..' . '/hidehalo/nanoid-php/src/Client.php',
        '_YabeWebfont\\Hidehalo\\Nanoid\\Core' => __DIR__ . '/..' . '/hidehalo/nanoid-php/src/Core.php',
        '_YabeWebfont\\Hidehalo\\Nanoid\\CoreInterface' => __DIR__ . '/..' . '/hidehalo/nanoid-php/src/CoreInterface.php',
        '_YabeWebfont\\Hidehalo\\Nanoid\\Generator' => __DIR__ . '/..' . '/hidehalo/nanoid-php/src/Generator.php',
        '_YabeWebfont\\Hidehalo\\Nanoid\\GeneratorInterface' => __DIR__ . '/..' . '/hidehalo/nanoid-php/src/GeneratorInterface.php',
        '_YabeWebfont\\Normalizer' => __DIR__ . '/..' . '/symfony/polyfill-intl-normalizer/Resources/stubs/Normalizer.php',
        '_YabeWebfont\\PhpToken' => __DIR__ . '/..' . '/symfony/polyfill-php80/Resources/stubs/PhpToken.php',
        '_YabeWebfont\\Psr\\Container\\ContainerExceptionInterface' => __DIR__ . '/..' . '/psr/container/src/ContainerExceptionInterface.php',
        '_YabeWebfont\\Psr\\Container\\ContainerInterface' => __DIR__ . '/..' . '/psr/container/src/ContainerInterface.php',
        '_YabeWebfont\\Psr\\Container\\NotFoundExceptionInterface' => __DIR__ . '/..' . '/psr/container/src/NotFoundExceptionInterface.php',
        '_YabeWebfont\\Rosua\\Migrations\\AbstractMigration' => __DIR__ . '/..' . '/rosua/migrations/src/AbstractMigration.php',
        '_YabeWebfont\\Rosua\\Migrations\\Command' => __DIR__ . '/..' . '/rosua/migrations/src/Command.php',
        '_YabeWebfont\\Rosua\\Migrations\\Configuration' => __DIR__ . '/..' . '/rosua/migrations/src/Configuration.php',
        '_YabeWebfont\\Rosua\\Migrations\\Exception\\AbortMigration' => __DIR__ . '/..' . '/rosua/migrations/src/Exception/AbortMigration.php',
        '_YabeWebfont\\Rosua\\Migrations\\Exception\\ControlException' => __DIR__ . '/..' . '/rosua/migrations/src/Exception/ControlException.php',
        '_YabeWebfont\\Rosua\\Migrations\\Exception\\MigrationException' => __DIR__ . '/..' . '/rosua/migrations/src/Exception/MigrationException.php',
        '_YabeWebfont\\Rosua\\Migrations\\Exception\\SkipMigration' => __DIR__ . '/..' . '/rosua/migrations/src/Exception/SkipMigration.php',
        '_YabeWebfont\\Rosua\\Migrations\\Generator' => __DIR__ . '/..' . '/rosua/migrations/src/Generator.php',
        '_YabeWebfont\\Rosua\\Migrations\\MigrationRepository' => __DIR__ . '/..' . '/rosua/migrations/src/MigrationRepository.php',
        '_YabeWebfont\\Rosua\\Migrations\\Migrator' => __DIR__ . '/..' . '/rosua/migrations/src/Migrator.php',
        '_YabeWebfont\\Stringable' => __DIR__ . '/..' . '/symfony/polyfill-php80/Resources/stubs/Stringable.php',
        '_YabeWebfont\\Symfony\\Component\\Finder\\Comparator\\Comparator' => __DIR__ . '/..' . '/symfony/finder/Comparator/Comparator.php',
        '_YabeWebfont\\Symfony\\Component\\Finder\\Comparator\\DateComparator' => __DIR__ . '/..' . '/symfony/finder/Comparator/DateComparator.php',
        '_YabeWebfont\\Symfony\\Component\\Finder\\Comparator\\NumberComparator' => __DIR__ . '/..' . '/symfony/finder/Comparator/NumberComparator.php',
        '_YabeWebfont\\Symfony\\Component\\Finder\\Exception\\AccessDeniedException' => __DIR__ . '/..' . '/symfony/finder/Exception/AccessDeniedException.php',
        '_YabeWebfont\\Symfony\\Component\\Finder\\Exception\\DirectoryNotFoundException' => __DIR__ . '/..' . '/symfony/finder/Exception/DirectoryNotFoundException.php',
        '_YabeWebfont\\Symfony\\Component\\Finder\\Finder' => __DIR__ . '/..' . '/symfony/finder/Finder.php',
        '_YabeWebfont\\Symfony\\Component\\Finder\\Gitignore' => __DIR__ . '/..' . '/symfony/finder/Gitignore.php',
        '_YabeWebfont\\Symfony\\Component\\Finder\\Glob' => __DIR__ . '/..' . '/symfony/finder/Glob.php',
        '_YabeWebfont\\Symfony\\Component\\Finder\\Iterator\\CustomFilterIterator' => __DIR__ . '/..' . '/symfony/finder/Iterator/CustomFilterIterator.php',
        '_YabeWebfont\\Symfony\\Component\\Finder\\Iterator\\DateRangeFilterIterator' => __DIR__ . '/..' . '/symfony/finder/Iterator/DateRangeFilterIterator.php',
        '_YabeWebfont\\Symfony\\Component\\Finder\\Iterator\\DepthRangeFilterIterator' => __DIR__ . '/..' . '/symfony/finder/Iterator/DepthRangeFilterIterator.php',
        '_YabeWebfont\\Symfony\\Component\\Finder\\Iterator\\ExcludeDirectoryFilterIterator' => __DIR__ . '/..' . '/symfony/finder/Iterator/ExcludeDirectoryFilterIterator.php',
        '_YabeWebfont\\Symfony\\Component\\Finder\\Iterator\\FileTypeFilterIterator' => __DIR__ . '/..' . '/symfony/finder/Iterator/FileTypeFilterIterator.php',
        '_YabeWebfont\\Symfony\\Component\\Finder\\Iterator\\FilecontentFilterIterator' => __DIR__ . '/..' . '/symfony/finder/Iterator/FilecontentFilterIterator.php',
        '_YabeWebfont\\Symfony\\Component\\Finder\\Iterator\\FilenameFilterIterator' => __DIR__ . '/..' . '/symfony/finder/Iterator/FilenameFilterIterator.php',
        '_YabeWebfont\\Symfony\\Component\\Finder\\Iterator\\LazyIterator' => __DIR__ . '/..' . '/symfony/finder/Iterator/LazyIterator.php',
        '_YabeWebfont\\Symfony\\Component\\Finder\\Iterator\\MultiplePcreFilterIterator' => __DIR__ . '/..' . '/symfony/finder/Iterator/MultiplePcreFilterIterator.php',
        '_YabeWebfont\\Symfony\\Component\\Finder\\Iterator\\PathFilterIterator' => __DIR__ . '/..' . '/symfony/finder/Iterator/PathFilterIterator.php',
        '_YabeWebfont\\Symfony\\Component\\Finder\\Iterator\\RecursiveDirectoryIterator' => __DIR__ . '/..' . '/symfony/finder/Iterator/RecursiveDirectoryIterator.php',
        '_YabeWebfont\\Symfony\\Component\\Finder\\Iterator\\SizeRangeFilterIterator' => __DIR__ . '/..' . '/symfony/finder/Iterator/SizeRangeFilterIterator.php',
        '_YabeWebfont\\Symfony\\Component\\Finder\\Iterator\\SortableIterator' => __DIR__ . '/..' . '/symfony/finder/Iterator/SortableIterator.php',
        '_YabeWebfont\\Symfony\\Component\\Finder\\Iterator\\VcsIgnoredFilterIterator' => __DIR__ . '/..' . '/symfony/finder/Iterator/VcsIgnoredFilterIterator.php',
        '_YabeWebfont\\Symfony\\Component\\Finder\\SplFileInfo' => __DIR__ . '/..' . '/symfony/finder/SplFileInfo.php',
        '_YabeWebfont\\Symfony\\Component\\PropertyAccess\\Exception\\AccessException' => __DIR__ . '/..' . '/symfony/property-access/Exception/AccessException.php',
        '_YabeWebfont\\Symfony\\Component\\PropertyAccess\\Exception\\ExceptionInterface' => __DIR__ . '/..' . '/symfony/property-access/Exception/ExceptionInterface.php',
        '_YabeWebfont\\Symfony\\Component\\PropertyAccess\\Exception\\InvalidArgumentException' => __DIR__ . '/..' . '/symfony/property-access/Exception/InvalidArgumentException.php',
        '_YabeWebfont\\Symfony\\Component\\PropertyAccess\\Exception\\InvalidPropertyPathException' => __DIR__ . '/..' . '/symfony/property-access/Exception/InvalidPropertyPathException.php',
        '_YabeWebfont\\Symfony\\Component\\PropertyAccess\\Exception\\NoSuchIndexException' => __DIR__ . '/..' . '/symfony/property-access/Exception/NoSuchIndexException.php',
        '_YabeWebfont\\Symfony\\Component\\PropertyAccess\\Exception\\NoSuchPropertyException' => __DIR__ . '/..' . '/symfony/property-access/Exception/NoSuchPropertyException.php',
        '_YabeWebfont\\Symfony\\Component\\PropertyAccess\\Exception\\OutOfBoundsException' => __DIR__ . '/..' . '/symfony/property-access/Exception/OutOfBoundsException.php',
        '_YabeWebfont\\Symfony\\Component\\PropertyAccess\\Exception\\RuntimeException' => __DIR__ . '/..' . '/symfony/property-access/Exception/RuntimeException.php',
        '_YabeWebfont\\Symfony\\Component\\PropertyAccess\\Exception\\UnexpectedTypeException' => __DIR__ . '/..' . '/symfony/property-access/Exception/UnexpectedTypeException.php',
        '_YabeWebfont\\Symfony\\Component\\PropertyAccess\\Exception\\UninitializedPropertyException' => __DIR__ . '/..' . '/symfony/property-access/Exception/UninitializedPropertyException.php',
        '_YabeWebfont\\Symfony\\Component\\PropertyAccess\\PropertyAccess' => __DIR__ . '/..' . '/symfony/property-access/PropertyAccess.php',
        '_YabeWebfont\\Symfony\\Component\\PropertyAccess\\PropertyAccessor' => __DIR__ . '/..' . '/symfony/property-access/PropertyAccessor.php',
        '_YabeWebfont\\Symfony\\Component\\PropertyAccess\\PropertyAccessorBuilder' => __DIR__ . '/..' . '/symfony/property-access/PropertyAccessorBuilder.php',
        '_YabeWebfont\\Symfony\\Component\\PropertyAccess\\PropertyAccessorInterface' => __DIR__ . '/..' . '/symfony/property-access/PropertyAccessorInterface.php',
        '_YabeWebfont\\Symfony\\Component\\PropertyAccess\\PropertyPath' => __DIR__ . '/..' . '/symfony/property-access/PropertyPath.php',
        '_YabeWebfont\\Symfony\\Component\\PropertyAccess\\PropertyPathBuilder' => __DIR__ . '/..' . '/symfony/property-access/PropertyPathBuilder.php',
        '_YabeWebfont\\Symfony\\Component\\PropertyAccess\\PropertyPathInterface' => __DIR__ . '/..' . '/symfony/property-access/PropertyPathInterface.php',
        '_YabeWebfont\\Symfony\\Component\\PropertyAccess\\PropertyPathIterator' => __DIR__ . '/..' . '/symfony/property-access/PropertyPathIterator.php',
        '_YabeWebfont\\Symfony\\Component\\PropertyAccess\\PropertyPathIteratorInterface' => __DIR__ . '/..' . '/symfony/property-access/PropertyPathIteratorInterface.php',
        '_YabeWebfont\\Symfony\\Component\\PropertyInfo\\DependencyInjection\\PropertyInfoConstructorPass' => __DIR__ . '/..' . '/symfony/property-info/DependencyInjection/PropertyInfoConstructorPass.php',
        '_YabeWebfont\\Symfony\\Component\\PropertyInfo\\DependencyInjection\\PropertyInfoPass' => __DIR__ . '/..' . '/symfony/property-info/DependencyInjection/PropertyInfoPass.php',
        '_YabeWebfont\\Symfony\\Component\\PropertyInfo\\Extractor\\ConstructorArgumentTypeExtractorInterface' => __DIR__ . '/..' . '/symfony/property-info/Extractor/ConstructorArgumentTypeExtractorInterface.php',
        '_YabeWebfont\\Symfony\\Component\\PropertyInfo\\Extractor\\ConstructorExtractor' => __DIR__ . '/..' . '/symfony/property-info/Extractor/ConstructorExtractor.php',
        '_YabeWebfont\\Symfony\\Component\\PropertyInfo\\Extractor\\PhpDocExtractor' => __DIR__ . '/..' . '/symfony/property-info/Extractor/PhpDocExtractor.php',
        '_YabeWebfont\\Symfony\\Component\\PropertyInfo\\Extractor\\PhpStanExtractor' => __DIR__ . '/..' . '/symfony/property-info/Extractor/PhpStanExtractor.php',
        '_YabeWebfont\\Symfony\\Component\\PropertyInfo\\Extractor\\ReflectionExtractor' => __DIR__ . '/..' . '/symfony/property-info/Extractor/ReflectionExtractor.php',
        '_YabeWebfont\\Symfony\\Component\\PropertyInfo\\Extractor\\SerializerExtractor' => __DIR__ . '/..' . '/symfony/property-info/Extractor/SerializerExtractor.php',
        '_YabeWebfont\\Symfony\\Component\\PropertyInfo\\PhpStan\\NameScope' => __DIR__ . '/..' . '/symfony/property-info/PhpStan/NameScope.php',
        '_YabeWebfont\\Symfony\\Component\\PropertyInfo\\PhpStan\\NameScopeFactory' => __DIR__ . '/..' . '/symfony/property-info/PhpStan/NameScopeFactory.php',
        '_YabeWebfont\\Symfony\\Component\\PropertyInfo\\PropertyAccessExtractorInterface' => __DIR__ . '/..' . '/symfony/property-info/PropertyAccessExtractorInterface.php',
        '_YabeWebfont\\Symfony\\Component\\PropertyInfo\\PropertyDescriptionExtractorInterface' => __DIR__ . '/..' . '/symfony/property-info/PropertyDescriptionExtractorInterface.php',
        '_YabeWebfont\\Symfony\\Component\\PropertyInfo\\PropertyInfoCacheExtractor' => __DIR__ . '/..' . '/symfony/property-info/PropertyInfoCacheExtractor.php',
        '_YabeWebfont\\Symfony\\Component\\PropertyInfo\\PropertyInfoExtractor' => __DIR__ . '/..' . '/symfony/property-info/PropertyInfoExtractor.php',
        '_YabeWebfont\\Symfony\\Component\\PropertyInfo\\PropertyInfoExtractorInterface' => __DIR__ . '/..' . '/symfony/property-info/PropertyInfoExtractorInterface.php',
        '_YabeWebfont\\Symfony\\Component\\PropertyInfo\\PropertyInitializableExtractorInterface' => __DIR__ . '/..' . '/symfony/property-info/PropertyInitializableExtractorInterface.php',
        '_YabeWebfont\\Symfony\\Component\\PropertyInfo\\PropertyListExtractorInterface' => __DIR__ . '/..' . '/symfony/property-info/PropertyListExtractorInterface.php',
        '_YabeWebfont\\Symfony\\Component\\PropertyInfo\\PropertyReadInfo' => __DIR__ . '/..' . '/symfony/property-info/PropertyReadInfo.php',
        '_YabeWebfont\\Symfony\\Component\\PropertyInfo\\PropertyReadInfoExtractorInterface' => __DIR__ . '/..' . '/symfony/property-info/PropertyReadInfoExtractorInterface.php',
        '_YabeWebfont\\Symfony\\Component\\PropertyInfo\\PropertyTypeExtractorInterface' => __DIR__ . '/..' . '/symfony/property-info/PropertyTypeExtractorInterface.php',
        '_YabeWebfont\\Symfony\\Component\\PropertyInfo\\PropertyWriteInfo' => __DIR__ . '/..' . '/symfony/property-info/PropertyWriteInfo.php',
        '_YabeWebfont\\Symfony\\Component\\PropertyInfo\\PropertyWriteInfoExtractorInterface' => __DIR__ . '/..' . '/symfony/property-info/PropertyWriteInfoExtractorInterface.php',
        '_YabeWebfont\\Symfony\\Component\\PropertyInfo\\Type' => __DIR__ . '/..' . '/symfony/property-info/Type.php',
        '_YabeWebfont\\Symfony\\Component\\PropertyInfo\\Util\\PhpDocTypeHelper' => __DIR__ . '/..' . '/symfony/property-info/Util/PhpDocTypeHelper.php',
        '_YabeWebfont\\Symfony\\Component\\PropertyInfo\\Util\\PhpStanTypeHelper' => __DIR__ . '/..' . '/symfony/property-info/Util/PhpStanTypeHelper.php',
        '_YabeWebfont\\Symfony\\Component\\Stopwatch\\Section' => __DIR__ . '/..' . '/symfony/stopwatch/Section.php',
        '_YabeWebfont\\Symfony\\Component\\Stopwatch\\Stopwatch' => __DIR__ . '/..' . '/symfony/stopwatch/Stopwatch.php',
        '_YabeWebfont\\Symfony\\Component\\Stopwatch\\StopwatchEvent' => __DIR__ . '/..' . '/symfony/stopwatch/StopwatchEvent.php',
        '_YabeWebfont\\Symfony\\Component\\Stopwatch\\StopwatchPeriod' => __DIR__ . '/..' . '/symfony/stopwatch/StopwatchPeriod.php',
        '_YabeWebfont\\Symfony\\Component\\String\\AbstractString' => __DIR__ . '/..' . '/symfony/string/AbstractString.php',
        '_YabeWebfont\\Symfony\\Component\\String\\AbstractUnicodeString' => __DIR__ . '/..' . '/symfony/string/AbstractUnicodeString.php',
        '_YabeWebfont\\Symfony\\Component\\String\\ByteString' => __DIR__ . '/..' . '/symfony/string/ByteString.php',
        '_YabeWebfont\\Symfony\\Component\\String\\CodePointString' => __DIR__ . '/..' . '/symfony/string/CodePointString.php',
        '_YabeWebfont\\Symfony\\Component\\String\\Exception\\ExceptionInterface' => __DIR__ . '/..' . '/symfony/string/Exception/ExceptionInterface.php',
        '_YabeWebfont\\Symfony\\Component\\String\\Exception\\InvalidArgumentException' => __DIR__ . '/..' . '/symfony/string/Exception/InvalidArgumentException.php',
        '_YabeWebfont\\Symfony\\Component\\String\\Exception\\RuntimeException' => __DIR__ . '/..' . '/symfony/string/Exception/RuntimeException.php',
        '_YabeWebfont\\Symfony\\Component\\String\\Inflector\\EnglishInflector' => __DIR__ . '/..' . '/symfony/string/Inflector/EnglishInflector.php',
        '_YabeWebfont\\Symfony\\Component\\String\\Inflector\\FrenchInflector' => __DIR__ . '/..' . '/symfony/string/Inflector/FrenchInflector.php',
        '_YabeWebfont\\Symfony\\Component\\String\\Inflector\\InflectorInterface' => __DIR__ . '/..' . '/symfony/string/Inflector/InflectorInterface.php',
        '_YabeWebfont\\Symfony\\Component\\String\\LazyString' => __DIR__ . '/..' . '/symfony/string/LazyString.php',
        '_YabeWebfont\\Symfony\\Component\\String\\Slugger\\AsciiSlugger' => __DIR__ . '/..' . '/symfony/string/Slugger/AsciiSlugger.php',
        '_YabeWebfont\\Symfony\\Component\\String\\Slugger\\SluggerInterface' => __DIR__ . '/..' . '/symfony/string/Slugger/SluggerInterface.php',
        '_YabeWebfont\\Symfony\\Component\\String\\UnicodeString' => __DIR__ . '/..' . '/symfony/string/UnicodeString.php',
        '_YabeWebfont\\Symfony\\Contracts\\Service\\Attribute\\Required' => __DIR__ . '/..' . '/symfony/service-contracts/Attribute/Required.php',
        '_YabeWebfont\\Symfony\\Contracts\\Service\\Attribute\\SubscribedService' => __DIR__ . '/..' . '/symfony/service-contracts/Attribute/SubscribedService.php',
        '_YabeWebfont\\Symfony\\Contracts\\Service\\ResetInterface' => __DIR__ . '/..' . '/symfony/service-contracts/ResetInterface.php',
        '_YabeWebfont\\Symfony\\Contracts\\Service\\ServiceLocatorTrait' => __DIR__ . '/..' . '/symfony/service-contracts/ServiceLocatorTrait.php',
        '_YabeWebfont\\Symfony\\Contracts\\Service\\ServiceProviderInterface' => __DIR__ . '/..' . '/symfony/service-contracts/ServiceProviderInterface.php',
        '_YabeWebfont\\Symfony\\Contracts\\Service\\ServiceSubscriberInterface' => __DIR__ . '/..' . '/symfony/service-contracts/ServiceSubscriberInterface.php',
        '_YabeWebfont\\Symfony\\Contracts\\Service\\ServiceSubscriberTrait' => __DIR__ . '/..' . '/symfony/service-contracts/ServiceSubscriberTrait.php',
        '_YabeWebfont\\Symfony\\Contracts\\Service\\Test\\ServiceLocatorTest' => __DIR__ . '/..' . '/symfony/service-contracts/Test/ServiceLocatorTest.php',
        '_YabeWebfont\\Symfony\\Polyfill\\Ctype\\Ctype' => __DIR__ . '/..' . '/symfony/polyfill-ctype/Ctype.php',
        '_YabeWebfont\\Symfony\\Polyfill\\Intl\\Grapheme\\Grapheme' => __DIR__ . '/..' . '/symfony/polyfill-intl-grapheme/Grapheme.php',
        '_YabeWebfont\\Symfony\\Polyfill\\Intl\\Normalizer\\Normalizer' => __DIR__ . '/..' . '/symfony/polyfill-intl-normalizer/Normalizer.php',
        '_YabeWebfont\\Symfony\\Polyfill\\Mbstring\\Mbstring' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/Mbstring.php',
        '_YabeWebfont\\Symfony\\Polyfill\\Php80\\Php80' => __DIR__ . '/..' . '/symfony/polyfill-php80/Php80.php',
        '_YabeWebfont\\Symfony\\Polyfill\\Php80\\PhpToken' => __DIR__ . '/..' . '/symfony/polyfill-php80/PhpToken.php',
        '_YabeWebfont\\UnhandledMatchError' => __DIR__ . '/..' . '/symfony/polyfill-php80/Resources/stubs/UnhandledMatchError.php',
        '_YabeWebfont\\ValueError' => __DIR__ . '/..' . '/symfony/polyfill-php80/Resources/stubs/ValueError.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit7af963c208bee77414c8dd2a06772197::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7af963c208bee77414c8dd2a06772197::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit7af963c208bee77414c8dd2a06772197::$classMap;

        }, null, ClassLoader::class);
    }
}
