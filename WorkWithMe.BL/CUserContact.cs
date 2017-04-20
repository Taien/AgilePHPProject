using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using WorkWithMe.PL;

namespace WorkWithMe.BL
{
    public class CUserContact
    {
        public Guid Id { get; set; }
        public Guid OwnerUserId { get; set; }
        public Guid TargetUserId { get; set; }
        public int InviteStatusId { get; set; }
        public string OwnerUserFullName { get; set; }
        public string TargetUserFullName { get; set; }
        public string InviteStatusDescription { get; set; }

        public CUserContact()
        {

        }

        public CUserContact(Guid id)
        {
            Id = id;
        }

        public CUserContact(Guid ownerUserId, Guid targetUserId, int inviteStatusId)
        {
            OwnerUserId = ownerUserId;
            TargetUserId = targetUserId;
            InviteStatusId = inviteStatusId;
        }

        public CUserContact(Guid id, Guid ownerUserId, Guid targetUserId, int inviteStatusId)
        {
            Id = id;
            OwnerUserId = ownerUserId;
            TargetUserId = targetUserId;
            InviteStatusId = inviteStatusId;
        }

        public CUserContact(Guid id, Guid ownerUserId, Guid targetUserId, int inviteStatusId, string ownerUserFullName, string targetUserFullName, string inviteStatusDescription)
        {
            Id = id;
            OwnerUserId = ownerUserId;
            TargetUserId = targetUserId;
            InviteStatusId = inviteStatusId;
            OwnerUserFullName = ownerUserFullName;
            TargetUserFullName = targetUserFullName;
            InviteStatusDescription = inviteStatusDescription;
        }

        public void Create()
        {
            using (WorkWithMeDataContext oDC = new WorkWithMeDataContext())
            {
                tblUserContact c = new tblUserContact();
                c.Id = Guid.NewGuid();
                c.OwnerUserId = OwnerUserId;
                c.TargetUserId = TargetUserId;
                c.InviteStatusId = InviteStatusId;

                tblInviteStatus inviteStatus = (from i in oDC.tblInviteStatus where i.Id == InviteStatusId select i).FirstOrDefault();
                InviteStatusDescription = inviteStatus.Description;

                tblUser user = (from u in oDC.tblUsers where u.Id == OwnerUserId select u).FirstOrDefault();
                OwnerUserFullName = user.FirstName + " " + user.LastName;

                tblUser targetUser = (from u in oDC.tblUsers where u.Id == TargetUserId select u).FirstOrDefault();
                TargetUserFullName = targetUser.FirstName + " " + targetUser.LastName;

                oDC.tblUserContacts.InsertOnSubmit(c);
                oDC.SubmitChanges();
            }
        }

        public void Update()
        {
            using (WorkWithMeDataContext oDC = new WorkWithMeDataContext())
            {
                tblUserContact contact = (from c in oDC.tblUserContacts where c.Id == Id select c).FirstOrDefault();

                contact.Id = Id;
                contact.OwnerUserId = OwnerUserId;
                contact.TargetUserId = TargetUserId;
                contact.InviteStatusId = InviteStatusId;

                tblInviteStatus inviteStatus = (from i in oDC.tblInviteStatus where i.Id == InviteStatusId select i).FirstOrDefault();
                InviteStatusDescription = inviteStatus.Description;

                tblUser user = (from u in oDC.tblUsers where u.Id == OwnerUserId select u).FirstOrDefault();
                OwnerUserFullName = user.FirstName + " " + user.LastName;

                tblUser targetUser = (from u in oDC.tblUsers where u.Id == TargetUserId select u).FirstOrDefault();
                TargetUserFullName = targetUser.FirstName + " " + targetUser.LastName;

                oDC.SubmitChanges();
            }
        }

        public void Delete()
        {
            using (WorkWithMeDataContext oDC = new WorkWithMeDataContext())
            {
                tblUserContact contact = (from c in oDC.tblUserContacts where c.Id == Id select c).FirstOrDefault();
                oDC.tblUserContacts.DeleteOnSubmit(contact);
                oDC.SubmitChanges();
            }
        }
    }

    public class CUserContactList : List<CUserContact>
    {
        public void LoadContactsForUser(Guid id)
        {
            Clear();
            using (WorkWithMeDataContext oDC = new WorkWithMeDataContext())
            {
                List<tblUserContact> contacts = (from c in oDC.tblUserContacts where (c.OwnerUserId == id || c.TargetUserId == id) && c.InviteStatusId == 2 select c).ToList();
                foreach (tblUserContact c in contacts)
                {
                    tblInviteStatus inviteStatus = (from i in oDC.tblInviteStatus where i.Id == c.InviteStatusId select i).FirstOrDefault();
                    string inviteStatusDescription = inviteStatus.Description;

                    tblUser user = (from u in oDC.tblUsers where u.Id == c.OwnerUserId select u).FirstOrDefault();
                    string ownerUserFullName = user.FirstName + " " + user.LastName;

                    tblUser targetUser = (from u in oDC.tblUsers where u.Id == c.TargetUserId select u).FirstOrDefault();
                    string targetUserFullName = targetUser.FirstName + " " + targetUser.LastName;

                    Add(new CUserContact(c.Id, c.OwnerUserId, c.TargetUserId, c.InviteStatusId, ownerUserFullName, targetUserFullName, inviteStatusDescription));
                }
            }
        }

        public void LoadInvitesForUser(Guid id)
        {
            Clear();
            using (WorkWithMeDataContext oDC = new WorkWithMeDataContext())
            {
                List<tblUserContact> contacts = (from c in oDC.tblUserContacts where c.TargetUserId == id && c.InviteStatusId == 0 select c).ToList();
                foreach (tblUserContact c in contacts)
                {
                    tblInviteStatus inviteStatus = (from i in oDC.tblInviteStatus where i.Id == c.InviteStatusId select i).FirstOrDefault();
                    string inviteStatusDescription = inviteStatus.Description;

                    tblUser user = (from u in oDC.tblUsers where u.Id == c.OwnerUserId select u).FirstOrDefault();
                    string ownerUserFullName = user.FirstName + " " + user.LastName;

                    tblUser targetUser = (from u in oDC.tblUsers where u.Id == c.TargetUserId select u).FirstOrDefault();
                    string targetUserFullName = targetUser.FirstName + " " + targetUser.LastName;

                    Add(new CUserContact(c.Id, c.OwnerUserId, c.TargetUserId, c.InviteStatusId, ownerUserFullName, targetUserFullName, inviteStatusDescription));
                }
            }
        }
    }
}
