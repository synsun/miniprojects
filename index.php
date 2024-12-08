<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SimpL.kr</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f4f7fc;
            font-family: 'Helvetica Neue', Arial, sans-serif;
            color: #333;
        }
        .header {
            background-color: #1a1a1a;
            color: white;
            padding: 20px 0;
            text-align: center;
        }
        .header a {
            color: #ff6f61;
            text-decoration: none;
            font-weight: bold;
            font-size: 24px; /* 글자 크기를 동일하게 설정 */
        }
        .header a:hover {
            color: #ff9f91;
        }
        .header .small-text {
            font-size: 24px; /* 글자 크기를 동일하게 설정 */
            color: #ddd;
        }
        .project-list {
            margin-top: 40px;
        }
        .list-group-item {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 15px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: pointer;
            color: inherit;
        }
        .list-group-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        .list-group-item:hover .project-name {
            color: #ff6f61;
        }
        .item-header, .description {
            text-decoration: none;
        }
        .item-header {
            display: flex;
            align-items: center;
            justify-content: flex-start;
        }
        .date {
            font-weight: bold;
            color: #6c757d;
            margin-right: 15px;
        }
        .project-name {
            font-weight: bold;
            font-size: 18px;
            color: #333;
        }
        .description {
            margin-top: 10px;
            color: #6c757d;
        }
        .footer {
            margin-top: 30px;
            padding: 20px 0;
            background-color: #1a1a1a;
            color: white;
            text-align: center;
        }
        @media (max-width: 576px) {
            .item-header {
                flex-direction: column;
                align-items: flex-start;
            }
            .date {
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-title">
            <a href="https://simpl.kr/">SimpL.kr</a>
            <span class="small-text">small project listing</span>
        </div>
    </div>
    <div class="container project-list">
        <ul class="list-group mt-3">
            <?php
            // Get the current directory
            $dir = __DIR__;

            // Load descriptions from JSON file
            $descriptionsFile = 'descriptions.json';
            $descriptions = [];
            if (file_exists($descriptionsFile)) {
                $descriptions = json_decode(file_get_contents($descriptionsFile), true);
            }

            // Scan the directory
            $directories = array_filter(glob($dir . '/*'), 'is_dir');

            // Create an array to hold directories with their creation dates
            $directoryData = [];
            $years = [];

            // Iterate over the directories
            foreach ($directories as $directory) {
                // Get the directory name
                $name = basename($directory);

                // Get the directory creation date
                $creationDate = date("Y-m-d", filemtime($directory));
                $year = date("Y", filemtime($directory));
                $years[] = $year;

                // Store the directory data
                $directoryData[] = ['name' => $name, 'date' => $creationDate, 'path' => $directory, 'description' => $descriptions[$name] ?? ''];
            }

            // Sort the directories by date in descending order
            usort($directoryData, function($a, $b) {
                return strtotime($b['date']) - strtotime($a['date']);
            });

            // Output the sorted directories
            foreach ($directoryData as $data) {
                echo "<li class='list-group-item' onclick=\"location.href='./{$data['name']}'\">";
                echo "<div class='item-header'><span class='date'>{$data['date']}</span><span class='project-name'>{$data['name']}</span></div>";
                if (!empty($data['description'])) {
                    echo "<div class='description'>{$data['description']}</div>";
                }
                echo "</li>";
            }

            // Determine the range of years
            $years = array_unique($years);
            sort($years);
            $yearRange = count($years) > 1 ? "{$years[0]} - " . end($years) : "{$years[0]}";
            ?>
        </ul>
    </div>
    <div class="footer">
        <p>&copy; <?php echo $yearRange; ?> SimpL.kr. All rights reserved.</p>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>
