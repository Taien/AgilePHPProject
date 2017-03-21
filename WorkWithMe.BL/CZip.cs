using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using WorkWithMe.PL; 

namespace WorkWithMe.BL
{
    public class CZip
    {
        public int Id { get; set; }
        public Guid CityId { get; set; }
        public Guid StateId { get; set; }

        public CZip() { }

        public CZip(int id, Guid cityId, Guid stateId)
        {
            Id = id;
            CityId = cityId;
            StateId = stateId; 
        }

        public void Create()
        {
            try
            {
                WorkWithMeDataContext oDC = new WorkWithMeDataContext();

                tblZip z = new tblZip();
                z.Id = Id;
                

                oDC.tblZips.InsertOnSubmit(z);
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
                tblZip zip = (from z in oDC.tblZips where z.Id == Id select z).FirstOrDefault();

                zip.Id = Id;

                oDC.SubmitChanges();
            }
        }

        public void Delete()
        {
            using (WorkWithMeDataContext oDC = new WorkWithMeDataContext())
            {
                tblZip zip = (from z in oDC.tblZips where z.Id == Id select z).FirstOrDefault();

                oDC.tblZips.DeleteOnSubmit(zip);
                oDC.SubmitChanges();
            }
        }
    }
}
