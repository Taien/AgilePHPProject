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
        public int CityId { get; set; }
        public int StateId { get; set; }

        public CZip() { }

        public CZip(int id, int cityid, int stateid)
        {
            Id = id;
            CityId = cityid;
            StateId = stateid; 
        }

        public void Insert(CZip zip)
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
