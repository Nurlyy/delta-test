import mysql.connector

connection = mysql.connector.connect(
    user='root', password='', host='localhost', database='delta-test', port=3306
)

cursor = connection.cursor()

with open('Test.txt', errors='ignore', encoding='utf-8') as f:
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
            temp[line.strip()] = {}
        else:
            if (line.strip()[0] == '*'):
                question_name = line.strip()
                temp[theme_name][question_name] = []
            elif (line.strip()[1] == '.' or line.strip()[2] == '.'):
                # print(line)
                temp[theme_name][question_name].append(line.strip())

# print(len(theme_names))



# try:
#     # query = f'SELECT * FROM theme;'
#     query = f'SELECT * FROM question;'
#     cursor.execute(query)
#     # themes = cursor.fetchall()
#     questions_query = cursor.fetchall()
#     # for theme in themes:
#     #     for theme_name in temp:
#     #         if(theme[1] == theme_name):
#     #             # print(theme_name)
#     #             for questions in temp[theme_name]:
#     #                 for variant in temp[theme_name][questions]:
                            
#     #                     # print('     ' + questions)
#     #                     query = f'INSERT INTO question (title, question_id) VALUES ("'+variant+f'", {theme[0]})'
#     #                     # cursor.execute(query)
#     #                     print(query)
        
#     for question in questions_query:
#         for theme_name in temp:
#             for questions in temp[theme_name]:
#                 if(question[1] == questions):
#                     for variant in temp[theme_name][questions]:
#                         is_right = 0
#                         if(variant[3] == '+'):
#                             is_right = 1
#                             variant = variant.replace("+", "", 1)
                        
#                         # print('     ' + questions)
#                         query = f'INSERT INTO variant (title, question_id, is_right) VALUES ("'+variant+f'", {question[0]}, {is_right})'
#                         cursor.execute(query)
#                         # print(query)
        

# # Commit your changes in the database
#     connection.commit()

# except Exception() as e:
#     # Rolling back in case of error
#     connection.rollback()
#     print(e)
#     pass
    
# print('success')
