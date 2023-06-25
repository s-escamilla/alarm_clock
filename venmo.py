from venmo_api import Client
import json
  
import random

friend_ls = []
friend_dict = {}
note = "Overslept"
f = open('data.json')
  
data = json.load(f)

user_id = data["User"]["id"]
# access_token = Client.get_access_token(username=data["User"]['user_name'],
#                                        password=data["User"]["password"])
access_token = data["Venmo_auth_token"]["token"]
client = Client(access_token=access_token)

f.close()

for i in data['Friends']["list"]:
    friend_ls.append(i["user_name"])

users = client.user.get_user_friends_list(user_id =user_id)
for user in users:
    friend_dict[user.username] = user.id

for friend in friend_ls:
    if friend_dict[friend] != None:
        print('Friend Name: {0} \nFriend user_id:{1}'.format(friend,friend_dict[friend]))

# print(client.payment.get_default_payment_method().name)
payment_methods = client.payment.get_payment_methods()
# print(str(client.payment.get_default_payment_method().id))
for payment_method in payment_methods:
    
    if str(payment_method.role).__contains__("BACKUP"):
        print('payment Type:{0} \nPayment ID: {1}'.format(payment_method.name,payment_method.id))
        funding_source_id = payment_method.id

rand_friend_id = friend_dict[friend_ls[random.randint(0,len(friend_ls)-1)]]

# client.payment.send_money(amount = float(1.00), note=note,target_user_id=int(rand_friend_id),funding_source_id=funding_source_id)
# client.log_out(access_token=access_token)
