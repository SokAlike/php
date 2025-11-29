<?php
header("Content-Type: application/json");

// =======================
// All Allowed PAIRS (OTC FORMAT)
// =======================
$all_pairs = [
    "BHDCNY-OTC","USDEGP-OTC","USDRUB-OTC","USDBDT-OTC","USDBRL-OTC",
    "EURUSD-OTC","AEDCNY-OTC","USDVND-OTC","CHFJPY-OTC","USDJPY-OTC",
    "EURCHF-OTC","EURNZD-OTC","USDDZD-OTC","EURGBP-OTC","NZDUSD-OTC",
    "AUDUSD-OTC","USDCHF-OTC","JODCNY-OTC","AUDCAD-OTC","USDCAD-OTC",
    "AUDJPY-OTC","USDARS-OTC","EURTRY-OTC","USDMYR-OTC","SARCNY-OTC",
    "USDCNH-OTC","GBPUSD-OTC","CADJPY-OTC","USDPHP-OTC","USDPKR-OTC",
    "USDTHB-OTC","AUDCHF-OTC","EURRUB-OTC","QARCNY-OTC","USDIDR-OTC",
    "USDINR-OTC","YERUSD-OTC","CADCHF-OTC","NZDJPY-OTC","USDMXN-OTC",
    "KESUSD-OTC","LBPUSD-OTC","NGNUSD-OTC","ZARUSD-OTC","UAHUSD-OTC",
    "CHFNOK-OTC","MADUSD-OTC","USDSGD-OTC","OMRCNY-OTC","USDCOP-OTC",
    "USDCLP-OTC","EURJPY-OTC","TNDUSD-OTC","EURHUF-OTC"
];

// =======================
// Get Parameters
// =======================
$count = isset($_GET['count']) ? max(1,intval($_GET['count'])) : 1;
$specific_param = isset($_GET['specific_pairs']) ? $_GET['specific_pairs'] : "";

// Process requested pairs
if ($specific_param != "") {
    $requested_pairs = array_map('trim', explode(",", strtoupper($specific_param)));
    // Only allow valid pairs
    $pairs = array_values(array_intersect($requested_pairs, $all_pairs));
    if (empty($pairs)) {
        echo json_encode([
            "status" => "error",
            "message" => "No valid pairs found in your request.",
            "allowed_pairs" => $all_pairs
        ], JSON_PRETTY_PRINT);
        exit();
    }
} else {
    $pairs = $all_pairs; // default: all pairs
}

// =======================
// Random Generators
// =======================
function random_time() {
    $h = str_pad(rand(0,23), 2, "0", STR_PAD_LEFT);
    $m = str_pad(rand(0,59), 2, "0", STR_PAD_LEFT);
    return "$h:$m";
}

function random_signal() {
    return rand(0,1) ? "CALL" : "PUT";
}

// =======================
// Generate Signals
// =======================
$signals = [];

foreach ($pairs as $pair) {
    for ($i = 0; $i < $count; $i++) {
        $signals[] = [
            "pair" => $pair,
            "signal" => random_signal(),
            "time" => random_time(),
            "timeframe" => "1M"
        ];
    }
}

// =======================
// Output JSON
// =======================
echo json_encode([
    "status" => "success",
    "total_pairs" => count($pairs),
    "signals_per_pair" => $count,
    "total_signals" => count($signals),
    "signals" => $signals
], JSON_PRETTY_PRINT);
?>
