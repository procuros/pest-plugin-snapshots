<?php

namespace Spatie\Snapshots\Concerns;

use ReflectionClass;

trait SnapshotIdAware
{
    /*
     * Determines the snapshot's id. By default, the test case's class and
     * method names are used.
     * 
     * Keeping a fork for now until the below PR is merged.
     * 
     * @see https://github.com/spatie/pest-plugin-snapshots/pull/16/files
     */
    protected function getSnapshotId(): string
    {
        return sprintf(
            '%s__%s__%s',
            (new ReflectionClass($this))->getShortName(),
            str_replace('__pest_evaluable_', '', str_replace(' ', '_', $this->nameWithDataSet())),
            $this->snapshotIncrementor
        );
    }
}
