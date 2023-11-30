import streamlit as st
import pymysql
import os

# config for the database
connection = pymysql.connect(host='localhost',
                             user='root',
                             password='pavani19',
                             db='payroll',
                             cursorclass=pymysql.cursors.DictCursor)
connection.autocommit(True)
cursor = connection.cursor()

st.write("# SQL Query")
st.write("## Enter SQL Query")
query = st.text_input("Enter SQL Query")

st.write("## Results")
# Execute the query
if st.button("Execute"):
    if query == "":
        st.error("Please enter a query")
    else:
        cursor.execute(query)
        result = cursor.fetchall()
        st.dataframe(result)
