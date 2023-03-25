<?php

namespace Spatie\Snapshots\Concerns;

use ReflectionClass;

trait SnapshotIdAware
{
    /*
     * Keeping a fork for now until the below PR is merged.
     * 
     * @see https://github.com/spatie/pest-plugin-snapshots/pull/16/files
     * 
     * The problem is that even if the PR above is merged, the snapshot files
     * will still likely be re-generated based on how Pest is normalzing the new
     * test case names.
     */
    protected function getSnapshotId(): string
    {
        $normalizedName = $this->getNormalizedTestName();

        $snapshotId = sprintf(
            '%s__%s',
            (new ReflectionClass($this))->getShortName(), 
            $normalizedName
        );

        if ($this->usesDataProvider()) {
            $snapshotId .= sprintf(
                '_with_data_set_%s',
                $this->getNormalizedDatasetName($this->dataName()),
            );
        }

        $snapshotId .= '__' . $this->snapshotIncrementor;
        
        return $snapshotId;
    }

    private function getNormalizedTestName(): string
    {
        return str_replace('__pest_evaluable_', '', $this->name());
    }

    private function getNormalizedDatasetName(string $datasetName): string
    {
        $normalizedDatasetName = $datasetName;

        preg_match('/"([^"]+)"/', $datasetName, $matches);
        if (strlen(trim($matches[1] ?? '')) > 0) {
            $normalizedDatasetName = $matches[1];
        }

        return str_replace(' ', '_', $normalizedDatasetName);
    }
}
