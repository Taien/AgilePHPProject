using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using WorkWithMe.PL; 

namespace WorkWithMe.BL
{
    public class CInviteStatus
    {
        public int Id { get; set; }
        public string Description { get; set; }

        public CInviteStatus() { }

        public CInviteStatus(int id, string description)
        {
            Id = id;
            Description = description;
        }

        public void Insert(CInviteStatus invitestatus)
        {
            try
            {
                WorkWithMeDataContext oDC = new WorkWithMeDataContext();
                tblInviteStatus i = new tblInviteStatus();
                i.Id = Id;
                i.Description = Description;

                oDC.tblInviteStatus.InsertOnSubmit(i);
                oDC.SubmitChanges();

            }
            catch (Exception ex)
            {
                throw ex;
            }
        }

        public void Update()
        {
            using (WorkWithMeDataContext oDC = new WorkWithMeDataContext())
            {
                tblInviteStatus invitestatus = (from i in oDC.tblInviteStatus where i.Id == Id select i).FirstOrDefault();

                invitestatus.Id = Id;
                invitestatus.Description = Description; 

                oDC.SubmitChanges();
            }
        }

        public void Delete()
        {
            using (WorkWithMeDataContext oDC = new WorkWithMeDataContext())
            {
                tblInviteStatus invitestatus = (from i in oDC.tblInviteStatus where i.Id == Id select i).FirstOrDefault();

                oDC.tblInviteStatus.DeleteOnSubmit(invitestatus);
                oDC.SubmitChanges();
            }
        }

    }
}
