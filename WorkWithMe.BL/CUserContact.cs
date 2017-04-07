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

        public void Create()
        {
            using (WorkWithMeDataContext oDC = new WorkWithMeDataContext())
            {
                tblUserContact c = new tblUserContact();
                c.Id = Guid.NewGuid();
                c.OwnerUserId = OwnerUserId;
                c.TargetUserId = TargetUserId;
                c.InviteStatusId = InviteStatusId;

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
                foreach (tblUserContact c in contacts) Add(new CUserContact(c.Id, c.OwnerUserId, c.TargetUserId, c.InviteStatusId));
            }
        }

        public void LoadInvitesForUser(Guid id)
        {
            Clear();
            using (WorkWithMeDataContext oDC = new WorkWithMeDataContext())
            {
                List<tblUserContact> contacts = (from c in oDC.tblUserContacts where c.TargetUserId == id && c.InviteStatusId == 0 select c).ToList();
                foreach (tblUserContact c in contacts) Add(new CUserContact(c.Id, c.OwnerUserId, c.TargetUserId, c.InviteStatusId));
            }
        }
    }
}
