import mysql.connector
import html

connection = mysql.connector.connect(
    user='root', password='', host='localhost', database='delta-test', port=3306
)

cursor = connection.cursor()

with open('Test-parser.txt', errors='ignore', encoding='utf-8') as f:
    lines = f.readlines()

themes = {}
theme_names = []
temp = {}
temp_lines = []
temp_variants = []
temp2 = {}


def represents_int(s):
    try:
        int(s)
    except ValueError:
        return False
    else:
        return True


temp = {}
theme_name = ''
question_name = ''
temp_variants = []
right_answers = []
for line in lines:
    if (line.strip() != ''):
        if (represents_int(line.strip()[0]) == True):
            theme_names.append(line.strip())
            theme_name = line.strip()
            theme_name = theme_name.replace('`', "'")
            temp[line.strip()] = []
        else:
            if (line.strip()[0] == '*'):
                question_name = line.strip()[1:]
                # temp[theme_name] = []
                question_name = question_name.replace('`', "'")
                temp[theme_name].append(question_name)
                    
            # elif (line.strip()[1] == '.' or line.strip()[2] == '.'):
            #     # print(line)
            #     temp[theme_name][question_name].append(line.strip())

# print(temp)

theme_id = 32

try:
    # for theme_name in temp:
    #     print(theme_name, end="\n")
    #     theme_name = theme_name.replace('"', "'")
    #     query = f'INSERT INTO question (title, theme_id) VALUES ("'+html.escape(theme_name, quote=True)+f'", {theme_id})'
    #     cursor.execute(query)
    #     print(query)
        
    
#--------------------------------------------------------------------------------------------------------------------


    query = f'SELECT * FROM question where theme_id={theme_id};'
    cursor.execute(query)
    questions_query = cursor.fetchall()

    for question in questions_query:
        for theme_name in temp:
            if(question[1] == theme_name.replace('"', '&#x27;').replace('<', '&lt;').replace('>', '&gt;')):
                for variant in temp[theme_name]:
                    is_right = 0
                    if(variant[1] == '+'):
                        is_right = 1
                        variant = variant.replace("+", "", 1)
                        variant = variant.replace('"', "'")
                    
                    # print('     ' + questions)
                    query = f'INSERT INTO variant (title, question_id, is_right) VALUES ("'+html.escape(variant, quote=True)+f'", {question[0]}, {is_right})'
                    cursor.execute(query)
                    print(query)


# Commit your changes in the database
    connection.commit()

except Exception() as e:
    # Rolling back in case of error
    connection.rollback()
    print(e)
    # pass
    
print('success')
