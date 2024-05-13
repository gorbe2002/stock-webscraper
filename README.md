# Stock Webscraper 📈
In this class project, I was able to extract the top 25 most active stocks from Yahoo Finance, store this data in a MongoDB database, and created a webpage to display it.

# Detailed Explanation ✍️
For the first part, I used Python's Request module to get data from Yahoo's "Most Active Stocks Today" page (URL: https://finance.yahoo.com/most-active). To not overload the amount of requests sent to the website, this script only scrapes data every 3 minutes for a total of 15 minutes. After setting this up, I used Python's bs4 module to parse the URL and get the HTML tags that made it possible to get the data from the table. 

To store everything in a MongoDB database, I used the pymongo module to connect to my localhost port. After selecting the correct database and collection (table), I added an Index number for each stock and its respective Symbol, Name, Lowercase name (for sorting purposes), Price, Change, and Volume. I made sure to upload each stock after every iteration that the code ran to account for new stocks entering the top 25 and every current stocks' difference in Price, Change, and Volume.

In order to display the data stored in the MongoDB database, I used php to connect to it and wrote HTML code within the php code to display the stocks and their features. I also added a feature where you can sort each column to be either ascending or descending.

# How to Run ⚙️
- To extract and store the stocks into your MongoDB server, run: **stock_webscraper.py** (keep in mind that this program will run for 15 minutes)
- To display the stocks in your localhost, run: **stock_webscraper.php**
