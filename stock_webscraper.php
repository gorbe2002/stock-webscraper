<!-- Part 4: Analyze Data -->
<?php

// establish connection to database
$client = new MongoDB\Driver\Manager("mongodb://localhost:27017/");

// select the generated table
$query = new MongoDB\Driver\Query([],[]);
$document = $client->executeQuery('finance.stocks', $query);

// get the current sorting field and order
$sortField = isset($_GET['sort']) ? $_GET['sort'] : 'lowercase_name';
$sortOrder = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'desc' : 'asc';

// toggle the next sorting order
$nextSortingOrder = $sortOrder === 'asc' ? 'desc' : 'asc';

// output table and its header with sorting ability
echo "
<!DOCTYPE html>
<html>
<head>
  <title>
    Most Active Stocks Today
  </title>
  <h1>
    Most Active Stocks on NYSE  (The  New  York  Stock  Exchange)
  </h1>
  <style>
    table {
    }
    th, td {
      border: 1px solid black;
      padding: 15px;
    }
  </style>
</head>
<body>
  <table>
    <thead>
      <tr>
        <th>
          <a href=\"?sort=Index&order=$nextSortingOrder\">Index</a>
        </th>
        <th>
          <a href=\"?sort=Symbol&order=$nextSortingOrder\">Symbol</a>
        </th>
        <th>
          <a href=\"?sort=Name&order=$nextSortingOrder\">Name</a>
        </th>
        <th>
          <a href=\"?sort=Price (Intraday)&order=$nextSortingOrder\">Price (Intraday)</a>
        </th>
        <th>
          <a href=\"?sort=Change&order=$nextSortingOrder\">Change</a>
        </th>
        <th>
          <a href=\"?sort=Volume&order=$nextSortingOrder\">Volume</a>
        </th>
      </tr>
    </thead>
    <tbody>";

if ($sortField === 'name') {
    $sortField = ['lowercase_name' => ($sortOrder === 'desc' ? -1 : 1)];
} else {
    $sortField = [$sortField => ($sortOrder === 'desc' ? -1 : 1)];
}

$query = new MongoDB\Driver\Query([], ['sort' => $sortField]);
$document = $client->executeQuery('finance.stocks', $query);

// output table rows
foreach ($document as $doc) {
    echo "<tr>";
    echo "<td>".$doc->{'Index'}."</td>";
    echo "<td>".$doc->{'Symbol'}."</td>";
    echo "<td>".$doc->{'Name'}."</td>";
    echo "<td>".$doc->{'Price (Intraday)'}."</td>";
    if ($doc->{'Change'} > 0) {
        echo "<td>+".$doc->{'Change'}."</td>";
    } else {
        echo "<td>".$doc->{'Change'}."</td>";
    }
    echo "<td>".$doc->{'Volume'}."M</td>";
    echo "</tr>";
}

// close remaining tags
echo "</tbody>
</table>
</body>
</html>";

?>