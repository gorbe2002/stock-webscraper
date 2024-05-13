import requests
import time
from bs4 import BeautifulSoup
from pymongo import MongoClient

total_duration = 15 * 60  # 15 minutes in seconds
interval = 3 * 60  # 3 minutes in seconds
start_time = time.time()
index = 1
# pass_num = 1

while time.time() - start_time < total_duration:
    # Part 1: Get the URL and send an HTTP get request
    url = "https://finance.yahoo.com/most-active"
    r = requests.get(url)
    # print(r.status_code) # check to see if the request works (will output 200 if it succeeds)

    # Part 2: Parse the given URL to find relevant data
    soup = BeautifulSoup(r.text, "html.parser")

    tableBody = soup.find("tbody") # get the body of the table
    allStocks = tableBody.find_all("tr") # get all the stocks from the body of the table

    # Part 3: Add/Update data into MongoDB database
    client = MongoClient()

    finance_db = client["finance"] # select database
    stocks = finance_db["stocks"] # select collection (table)

    for stock in allStocks:
        symbol = stock.a.text # add current stock's Symbol
        name = stock.find("td", {"aria-label":"Name"}).text # adds current stock's Name
        lowercase_name = name.lower()
        price = float(stock.find("td", {"aria-label":"Price (Intraday)"}).text) # adds current stock's Price (Intraday)
        change = float(stock.find("td", {"aria-label":"Change"}).text) # adds current stock's Change
        volume = float(stock.find("td", {"aria-label":"Volume"}).text[:-1]) # adds current stock's Volume

        # check if the stock is already in the database
        knownStock = stocks.find_one({"Symbol":symbol})
        if knownStock:
            stocks.update_one({"_id":knownStock["_id"]}, {"$set": {"Price (Intraday)":price, "Change":change, "Volume":volume}})
        else:
            stocks.insert_one({"Index":index, "Symbol":symbol, "Name":name, "Lowercase Name":lowercase_name, "Price (Intraday)":price, "Change":change, "Volume":volume})
            index += 1
        
    # print(f"Pass #{pass_num} complete")
    # pass_num += 1
    time.sleep(interval)
