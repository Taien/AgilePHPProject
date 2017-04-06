using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using WorkWithMe.PL; 

namespace WorkWithMe.BL
{
   public class CGroupType
    {
        public int Id { get; set; }
        public string Description { get; set; }

        public CGroupType() { }

        public CGroupType(int id, string description)
        {
            Id = id;
            Description = description;
        }

        public CGroupType(string description)
        {
            Description = description; 
        }

        public CGroupType(int id)
        {
            Id = id; 
        }
        public void Create()
        {
            try
            {
                WorkWithMeDataContext oDC = new WorkWithMeDataContext();

                tblGroupType g = new tblGroupType();
                g.Id = Id;
                g.Description = Description;

                oDC.tblGroupTypes.InsertOnSubmit(g);
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
                tblGroupType grouptype = (from g in oDC.tblGroupTypes where g.Id == Id select g).FirstOrDefault();

                grouptype.Id = Id;
                grouptype.Description = Description;

                oDC.SubmitChanges();
            }
        }

        public void Delete()
        {
            using (WorkWithMeDataContext oDC = new WorkWithMeDataContext())
            {
                tblGroupType grouptype = (from g in oDC.tblGroupTypes where g.Id == Id select g).FirstOrDefault();

                oDC.tblGroupTypes.DeleteOnSubmit(grouptype);
                oDC.SubmitChanges();
            }
        }
    }
}
