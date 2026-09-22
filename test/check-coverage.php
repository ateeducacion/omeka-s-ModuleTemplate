<?php
declare(strict_types=1);

// Enforce the threshold locally and in CI, independently of Codecov availability.
$report = $argv[1] ?? 'coverage.xml';
$minimum = $argv[2] ?? '90';
if (!is_numeric($minimum) || $minimum < 0 || $minimum > 100) {
    fwrite(STDERR, "Coverage threshold must be between 0 and 100.\n");
    exit(1);
}
libxml_use_internal_errors(true);
$coverage = is_file($report) ? simplexml_load_file($report, 'SimpleXMLElement', LIBXML_NONET) : false;
$metrics = $coverage === false ? null : $coverage->project->metrics;
$total = (int) ($metrics['statements'] ?? 0);
$covered = (int) ($metrics['coveredstatements'] ?? 0);
if ($total <= 0 || $covered < 0 || $covered > $total) {
    fwrite(STDERR, "Missing or invalid Clover coverage metrics.\n");
    exit(1);
}
$percentage = 100 * $covered / $total;
printf("Line coverage: %.2f%% (%d/%d); required: %s%%\n", $percentage, $covered, $total, $minimum);
exit($percentage >= (float) $minimum ? 0 : 1);
