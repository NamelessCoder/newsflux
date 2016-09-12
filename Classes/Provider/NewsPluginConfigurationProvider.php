<?php
namespace NamelessCoder\Newsflux\Provider;

use FluidTYPO3\Flux\Provider\AbstractProvider;
use FluidTYPO3\Flux\Provider\ProviderInterface;
use FluidTYPO3\Flux\View\TemplatePaths;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * News Plugin Configuration Provider
 *
 * Implementation of Flux's Provider pattern, targeted at providing
 * additional features for instances of plugins from EXT:news.
 *
 * Allows placing a `flux:form` in an overridden template and have
 * any sheets/fields from this form added to the default EXT:news
 * FlexForm data structure.
 */
class NewsPluginConfigurationProvider extends AbstractProvider  implements ProviderInterface
{
    /**
     * @var string
     */
    protected $tableName = 'tt_content';

    /**
     * @var string
     */
    protected $fieldName = 'pi_flexform';

    /**
     * @var string
     */
    protected $contentObjectType = 'list';

    /**
     * @var string
     */
    protected $listType = 'news_pi1';

    /**
     * The Provider integration is solely for GeorgRinger.News
     * and the extension identity is hardcoded as such. This
     * causes correct resolving of template paths and settings
     * from the proper `plugin.tx_news.*` scopes.
     *
     * @var string
     */
    protected $extensionKey = 'GeorgRinger.News';

    /**
     * The only supported controller name for EXT:news is the
     * "News" controller - this method is hardcoded to return
     * only that controller name. This causes correct template
     * resolving in the `Resources/Private/Templates/News`
     * location of EXT:news template paths.
     *
     * @param array $row
     * @return string
     */
    public function getControllerNameFromRecord(array $row)
    {
        return 'News';
    }

    /**
     * Method responsible for returning the main controller
     * action name according to what is selected in the
     * `switchableControllerActions` FlexForm setting. The
     * method parses the current FlexForm XML and analyses
     * the selected controller action, and if any is selected
     * it returns the action part. E.g. if selected value if
     * `News->list` then `list` will be the value returned.
     * The `list` action then corresponds to a template based
     * on the standard Extbase MVC pattern.
     *
     * @param array $row
     * @return string
     */
    public function getControllerActionFromRecord(array $row)
    {
        $currentValues = $this->configurationService->convertFlexFormContentToArray($row[$this->fieldName]);
        if (isset($currentValues['switchableControllerActions'])) {
            $selectedControllerActions = GeneralUtility::trimExplode(',', $currentValues['switchableControllerActions']);
            if (isset($selectedControllerActions[0])) {
                return strtolower(substr($selectedControllerActions[0], strpos($selectedControllerActions[0], '->') + 2));
            }
        }
        return 'default';
    }

    /**
     * Method responsible for merging two different data
     * structures into one set of sheets. Due to the two
     * different methods Flux can use to return sheets (see
     * the Flux "compacting" feature!) this Provider needs
     * to check the structure of the Flux form and select the
     * right method for merging.
     *
     * Is also responsible for changing the default behavior
     * of a Provider, which is to DISCARD any existing data
     * structure rather than MERGE with the new one. Flux
     * only supports replacing an existing data structure so
     * we need to merge them manually.
     *
     * @param array $row
     * @param mixed $dataStructure
     * @param array $conf
     * @return void
     */
    public function postProcessDataStructure(array &$row, &$dataStructure, array $conf)
    {
        $form = $this->getForm($row);
        if (!$form) {
            return;
        }
        $newDataStructure = $form->build();
        if (isset($newDataStructure['sheets'])) {
            $dataStructure['sheets'] = array_replace_recursive(
                $dataStructure['sheets'],
                $newDataStructure['sheets']
            );
        } else {
            $dataStructure = array_replace_recursive(
                $dataStructure,
                $newDataStructure
            );
        }
    }

    /**
     * Method responsible for resolving the template file that
     * corresponds to a selected action, by using the standard
     * Extbase MVC conventions and looking in defined template
     * paths using the standard Fluid template resolving logic.
     *
     * @param array $row
     * @return NULL|string
     */
    public function getTemplatePathAndFilename(array $row)
    {
        $controllerAction = $this->getControllerActionFromRecord($row);
        $paths = new TemplatePaths($this->getExtensionKey($row));
        return $paths->resolveTemplateFileForControllerAndActionAndFormat('News', $controllerAction);
    }

}
