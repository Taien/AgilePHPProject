using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using WorkWithMe.PL;

namespace WorkWithMe.BL
{
    public class CUser
    {
        public Guid Id { get; set; }
        public string Username { get; set; }
        public string FirstName { get; set; }
        public string MiddleInitial { get; set; }
        public string LastName { get; set; }
        public int? Zip { get; set; }
        public string Address { get; set; }
        public bool IsAddressPrivate { get; set; }
        public string Email { get; set; }
        // public SomeKindOfImage Image { get; set; }

        public CUser()
        {

        }

        public CUser(Guid id)
        {
            Id = id;
        }

        public CUser(Guid id, string username, string firstName, string middleInitial, string lastName,
                     int? zip, string address, bool isAddressPrivate, string email/*, SomeKindOfImage image*/)
        {
            Id = id;
            Username = username;
            FirstName = firstName;
            MiddleInitial = middleInitial;
            LastName = lastName;
            Zip = zip;
            Address = address;
            IsAddressPrivate = isAddressPrivate;
            Email = email;
        }

        public CUser(string username, string firstName, string middleInitial, string lastName,
                     int? zip, string address, bool isAddressPrivate, string email/*, SomeKindOfImage image*/)
        {
            Username = username;
            FirstName = firstName;
            MiddleInitial = middleInitial;
            LastName = lastName;
            Zip = zip;
            Address = address;
            IsAddressPrivate = isAddressPrivate;
            Email = email;
        }

        public bool Create(string password, string city, string state, ref string response)
        {
            using (WorkWithMeDataContext oDC = new WorkWithMeDataContext())
            {
                if (oDC.spCreateUser(Username, password, FirstName, MiddleInitial, LastName, Zip, Address, city, state, IsAddressPrivate, Email, ref response) == 0) return false;
                else return true;
            }
        }

        public bool Login(string username, string password)
        {
            using (WorkWithMeDataContext oDC = new WorkWithMeDataContext())
            {
                spDoLoginResult result = oDC.spDoLogin(username, password).FirstOrDefault();
                if (result.Id != null)
                {
                    tblUser user = (from u in oDC.tblUsers where u.Id == result.Id select u).FirstOrDefault();
                    Id = user.Id;
                    Username = user.Username;
                    FirstName = user.FirstName;
                    MiddleInitial = user.MiddleInitial;
                    LastName = user.LastName;
                    Zip = user.Zip;
                    Address = user.Address;
                    IsAddressPrivate = user.IsAddressPrivate;
                    Email = user.EmailAddress;
                    return true;
                }
                else return false;
            }
        }

        public void Update()
        {
            using (WorkWithMeDataContext oDC = new WorkWithMeDataContext())
            {
                tblUser user = (from u in oDC.tblUsers where u.Id == Id select u).FirstOrDefault();
                user.Username = Username;
                user.FirstName = FirstName;
                user.MiddleInitial = MiddleInitial;
                user.LastName = LastName;
                user.Zip = Zip;
                user.Address = Address;
                user.IsAddressPrivate = IsAddressPrivate;
                user.EmailAddress = Email;

                oDC.SubmitChanges();
            }
        }

        public void Delete()
        {
            using (WorkWithMeDataContext oDC = new WorkWithMeDataContext())
            {
                tblUser user = (from u in oDC.tblUsers where u.Id == Id select u).FirstOrDefault();

                oDC.tblUsers.DeleteOnSubmit(user);
                oDC.SubmitChanges();
            }
        }

        public void Load()
        {
            using (WorkWithMeDataContext oDC = new WorkWithMeDataContext())
            {
                tblUser user = (from u in oDC.tblUsers where u.Id == Id select u).FirstOrDefault();
                Username = user.Username;
                FirstName = user.FirstName;
                MiddleInitial = user.MiddleInitial;
                LastName = user.LastName;
                Zip = user.Zip;
                Address = user.Address;
                IsAddressPrivate = user.IsAddressPrivate;
            }
        }
        
    }
      
    public class CUserList : List<CUser>
    {
        public void Load()
        {
            Clear();
            using (WorkWithMeDataContext oDC = new WorkWithMeDataContext())
            {
                List<tblUser> users = (from u in oDC.tblUsers select u).ToList();
                foreach (tblUser user in users) Add(new CUser(user.Id, user.Username, user.FirstName, user.MiddleInitial, user.LastName, user.Zip, user.Address, user.IsAddressPrivate, user.EmailAddress));
            }
        }
    }  
   
}
