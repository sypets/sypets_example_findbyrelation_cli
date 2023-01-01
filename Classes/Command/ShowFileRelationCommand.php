<?php
declare(strict_types=1);
namespace Sypets\SypetsExampleFindbyrelatationCli\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Core\SysLog\Action\File;
use TYPO3\CMS\Core\Utility\GeneralUtility;



class ShowFileRelationCommand extends Command
{
    protected InputInterface $input;
    protected OutputInterface $output;
    protected SymfonyStyle $io;

    // todo move the flexform functionality into a service
    protected ?FlexFormService $flexformService = null;
    protected ?FileRepository $fileRepository = null;

    protected const NAME = 'bibtex:dumpFlexform';
    protected const PLUGIN_SIGNATURE = 'sypetsexamplefindbyrelationcli_files';

    public function __construct()
    {
        parent::__construct(self::NAME);

        $this->flexformService = GeneralUtility::makeInstance(FlexFormService::class);
        $this->fileRepository = GeneralUtility::makeInstance(FileRepository::class);
    }

    protected function configure()
    {
        $this->setDescription('Dump information from flexforms for all plugins');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->input = $input;
        $this->output = $output;
        $this->io = new SymfonyStyle($input, $output);

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tt_content')->createQueryBuilder();
        $queryBuilder->select('uid', 'pid', 'pi_flexform', 'header')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq('ctype', $queryBuilder->createNamedParameter('list')),
                $queryBuilder->expr()->eq(
                    'list_type',
                    $queryBuilder->createNamedParameter(self::PLUGIN_SIGNATURE)
                )
            );
        $statement = $queryBuilder->execute();

        while ($row = $statement->fetchAssociative()) {
            $uid = (int)$row['uid'];
            $pid = (int)$row['pid'];

            $this->io->section(sprintf(
                '%s [%d] on page [%d]',
                $row['header'],
                $uid,
                $pid
            ));

            $strFlexform = $row['pi_flexform'] ?? '';
            if (!$strFlexform) {
                $this->io->warning(sprintf(
                    '%s [%d] on page [%d]: has no flexform',
                    $row['header'],
                    $uid,
                    $pid
                ));
                continue;
            }

            $xml = $row['pi_flexform'] ?? '';
            $xmlArray = $this->flexformXml2Settings($xml);
            $settings = $xmlArray['settings'] ?? [];
            if (!$settings) {
                $this->io->warning(sprintf(
                    '%s [%d] on page [%d]: has no valid Flexform with settings',
                    $row['header'],
                    $uid,
                    $pid
                ));
                continue;
            }

            $fileCount = (int)($settings['file'] ?? 0);
            $this->io->writeln('file count=' . $fileCount);
            if ($fileCount > 0) {
                // will throw exception!
                $fileObjects = $this->getFiles($uid);
                if (!$fileObjects) {
                    throw new \RuntimeException('No file objects returned by FileRepository::findByRelations although there should be file relations here! (expected exception to reproduce)');
                }
            }
        }
        $this->io->writeln('Done');
        return 0;
    }

    protected function flexformXml2Settings(string $xml): array
    {
        return $this->flexformService->convertFlexFormContentToArray($xml);
    }


    /**
     * @param int $uid
     * @return FileReference[]
     */
    protected function getFiles(int $uid): array
    {
        return $this->fileRepository->findByRelation('tt_content', 'pi_flexform', $uid);
    }

}
